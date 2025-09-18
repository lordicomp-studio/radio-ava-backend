<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $motherArticle = Permission::create(['name' => 'articles']); // mother permission

        Permission::create(['name' => 'all articles', 'parent_id' => $motherArticle->id]);
        Permission::create(['name' => 'edit articles', 'parent_id' => $motherArticle->id]);
        Permission::create(['name' => 'delete articles', 'parent_id' => $motherArticle->id]);
        Permission::create(['name' => 'publish articles', 'parent_id' => $motherArticle->id]);
        Permission::create(['name' => 'unpublish articles', 'parent_id' => $motherArticle->id]);


        $motherProduct = Permission::create(['name' => 'products']); // mother permission

        Permission::create(['name' => 'all products', 'parent_id' => $motherProduct->id]);
        Permission::create(['name' => 'edit products', 'parent_id' => $motherProduct->id]);
        Permission::create(['name' => 'delete products', 'parent_id' => $motherProduct->id]);
        Permission::create(['name' => 'publish products', 'parent_id' => $motherProduct->id]);
        Permission::create(['name' => 'unpublish products', 'parent_id' => $motherProduct->id]);

        $motherUser = Permission::create(['name' => 'users']); // mother permission

        Permission::create(['name' => 'all users', 'parent_id' => $motherUser->id]);
        Permission::create(['name' => 'edit users', 'parent_id' => $motherUser->id]);
        Permission::create(['name' => 'delete users', 'parent_id' => $motherUser->id]);
        Permission::create(['name' => 'publish users', 'parent_id' => $motherUser->id]);
        Permission::create(['name' => 'unpublish users', 'parent_id' => $motherUser->id]);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer', 'color' => '#009EF7']);
        $role->givePermissionTo('edit articles');

        // or may be done by chaining
        $role = Role::create(['name' => 'moderator', 'color' => '#50CD89'])
            ->givePermissionTo(['publish articles', 'unpublish articles']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // assign roles to users
        $users = User::take(3)->get();
        foreach ($users as $user){
            $user->assignRole($role);
        }

    }
}
