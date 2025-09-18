<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RoleShowResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();

        return Inertia::render('Admin/Roles/RolesIndex', compact('roles'));
    }

    public function getTableData(){
        // this is for updating index.
        return Role::with('permissions')->withCount('users')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function store(Request $request)
    {
        // ->givePermissionTo(['publish articles', 'unpublish articles'])
        $request->validate([
            'name' => 'required | min:3 | max:255 | unique:roles',
            'color' => 'required',
//            'permissionIds' => 'required',
        ]);

        $newRole = Role::create($request->toArray());
        $newRole->permissions()->attach($request->permissionIds);

        return 200;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Inertia\Response
     */
    public function show(Role $role)
    {
        $role = RoleShowResource::make(
            Role::where('id', $role->id)
                ->with(['permissions', 'users', 'users.profilePhoto'])
                ->withCount('users')->first()
        );

        return Inertia::render('Admin/Roles/RoleShow', [
            'role' => json_decode(json_encode($role))
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return int
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'color' => 'required',
//            'permissionIds' => 'required',
        ]);

        $role->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        $role->permissions()->sync($request->permissionIds);
        return 200;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return int
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return 200;
    }
}
