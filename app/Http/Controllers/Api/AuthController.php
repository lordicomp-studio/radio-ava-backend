<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RestApiAuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserInfoResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['me' , 'logout', 'refresh', 'verifyToken']);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|max:255|email',
            'password' => 'required|min:8|max:255',
        ]);

        $data = $request->only(['email' , 'password']);

        if (!User::whereEmail($data['email'])->first()){
            return response()->json([
                'errors'=> [
                    'email' => ['Your email was not found on our servers']
                ]
            ] , 401);
        }

        if (!Auth::attempt($data , $request->input('remember'))){
            return response()->json([
                'errors'=> [
                    'email' => ['Your login credentials are incorrect']
                ]
            ] , 401);
        }

        $user = User::whereEmail($request->email)->first();

        return $this->respondWithToken($user, $request->ip());
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|min:2|max:255',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|min:8|max:255|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return $this->respondWithToken($user, $request->ip());
    }

    public function me(Request $request){
        return UserInfoResource::make($request->user());
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => "You have successfully logged out",
        ],200);
    }

    public function refresh(Request $request){
        $request->user()->tokens()->delete();
        return $this->respondWithToken($request->user(), $request->ip());
    }

    public function forgetPassword(Request $request){
        $request->validate([
            'email' => 'required|max:255|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT){
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'An email with instructions to reset your password has been sent to your email address'
            ]);
        }else{
            return response()->json([
                'status' => true,
                'message' => trans($status)
            ]);
        }
    }

    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|max:255|email',
            'password' => ['required' , 'min:8' , 'max:255' , 'confirmed'],
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'),function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
            $user->tokens()->delete();
            event(new PasswordReset($user));
        });
        if ($status == Password::PASSWORD_RESET){
            return response()->json([
               'status' => true,
               'message' => 'Your password has successfully updated'
            ]);
        }
        return response()->json([
            'errors'=> [
                'email' => [__($status)]
            ]
        ] , 500);
    }

    public function verifyToken(){
        return response('Token is valid', 200);
    }

    protected function respondWithToken($user, string $ip){
        $plainTextToken = RestApiAuthHelper::makeNewPersonalAccessToken($user, $ip);

        return response()->json([
            'token' => $plainTextToken,
            'token_type' => 'bearer',
            'status' => true,
            'data' => $user,
        ],200);
    }
}
