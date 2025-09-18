<?php

namespace Database\Seeders;

use App\Enums\UserLevelEnum;
use App\Models\Article;
use App\Models\Category;
use App\Models\Medium;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Submenu;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);

        $this->call(PermissionsSeeder::class);

        $this->call(FakeDataSeeder::class);

        Artisan::call("storage:link");
    }
}
