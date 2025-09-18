<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PermissionIndexResource;
use App\Models\MyPermission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $permissions = PermissionIndexResource::collection(
            Permission::with('roles')->latest()->paginate(10)
        )->resource;
        $baseLink = '/admin/permissions/indexDataApi';

        return Inertia::render('Admin/Permissions/PermissionsIndex', [
            'permissions' => json_decode(json_encode($permissions)),
            'baseLink' => $baseLink,
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $permissions = PermissionIndexResource::collection(
            Permission::where('name', 'LIKE', "%$searchText%")->with('roles')->latest()->paginate(10)
        )->resource;

        return $permissions;
    }

    public function getParents(){
        return Permission::where('parent_id', 0)->get();
    }

    public function getAllParentChildren(){
        return $parents = MyPermission::where('parent_id', 0)->with('children')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | min:3 | max:255 | unique:permissions',
            'parent_name' => 'required | min:3 | max:255',
        ]);

        $parent_id = $request->parent_name == 'is parent' ? 0 :
            Permission::where('name', $request->parent_name)->first()->id;

        $newPermission = Permission::create([
            'name' => $request->name,
            'parent_id' => $parent_id,
        ]);

        if ($parent_id === 0){
            // if it is a parent permission we should add am "all " permission for it.
            Permission::create([
                'name' => 'show all ' . $newPermission->name,
                'parent_id' => $newPermission->id,
            ]);
        }

        return 200;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return int
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'parent_name' => 'required | min:3 | max:255',
        ]);

        $parent_id = $request->parent_name == 'is parent' ? 0 :
            Permission::where('name', $request->parent_name)->first()->id;

        $permission->update([
            'name' => $request->name,
            'parent_id' => $parent_id,
        ]);

        return 200;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return int
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return 200;
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Permission::destroy($request->toArray());
        return 200;
    }
}
