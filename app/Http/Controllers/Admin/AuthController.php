<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function loginPage(){
        return Inertia::render('Admin/Auth/loginPage');
    }

    public function loginPost(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated))
            return Inertia::location('/admin/dashboard/panel'); // redirects to dashboard


        // Return errors to the form
        return back()->withErrors([
            'email' => 'The provided email or password is incorrect.',
        ])->withInput(); // Keep old input for the form
    }

    public function logout(){
        Auth::logout();
    }

}
