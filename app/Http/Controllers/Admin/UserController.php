<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserLevelEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreAndUpdateRequest;
use App\Http\Resources\Admin\UserIndexResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Testing\Concerns\Has;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = UserIndexResource::collection(
            User::with('profilePhoto')->latest()->paginate(10)
        )->resource;

        return Inertia::render('Admin/Users/Index', [
            'data' => json_decode(json_encode($users)),
            'baseLink' => '/admin/users/indexDataApi',
            'type' => 'user',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $users = UserIndexResource::collection(
            User::with('profilePhoto')->where('name', 'LIKE', "%$searchText%")
                ->latest()->paginate(10)
        )->resource;

        return $users;
    }

    public function create()
    {
        $roles = Role::all()->pluck('name');
        $levels = UserLevelEnum::options();

        return Inertia::render('Admin/Users/CreateForm', compact('roles', 'levels'));
    }

    public function store(UserStoreAndUpdateRequest $request)
    {
        $fields = $request->safe()->except([
            'password', 'profilePhotoId',
        ]);

        $fields['profile_photo_id'] = $request->profilePhotoId;
        $fields['password'] = Hash::make($request->password);

        $user = User::create($fields);

        if ($fields['roleName'] != 'normalUser'){
            $user->assignRole($fields['roleName']);
        }

        return response(null, 200);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function edit(User $user)
    {
        $roles = Role::all()->pluck('name');
        $levels = UserLevelEnum::options();

        return Inertia::render('Admin/Users/CreateForm', [
            'roles' => $roles,
            'levels' => $levels,
            'isEdit' => true,
            'formerData' => $user->where('id', $user->id)->with('profilePhoto', 'roles')->first(),
        ]);
    }

    public function update(UserStoreAndUpdateRequest $request, User $user)
    {
        $fields = $request->safe()->except([
            'password', 'profilePhotoId',
        ]);

        $fields['profile_photo_id'] = $request->profilePhotoId;
        if (isset($request->password)){
            $fields['password'] = Hash::make($request->password);
        }

        $user->update($fields);

        if ($fields['roleName'] === 'normalUser'){
            $user->roles()->detach();
        }else{
            $user->syncRoles($fields['roleName']);
        }

        return response(null, 200);
    }

    public function destroy(User $user)
    {
        // TODO do the needed detaches
        $user->delete();
        return 200;
    }

    public function deleteMultiple(Request $request){
        // TODO do the needed detaches
        // $request is an array of permission ids
        User::destroy($request->toArray());
        return 200;
    }

}
