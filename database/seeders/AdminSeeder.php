<?php

namespace Database\Seeders;

use App\Enums\UserLevelEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'mehrab',
            'email' => 'mehrabesmailnia96@gmail.com',
            'password' => Hash::make('12341234'),
            'level' => UserLevelEnum::Admin->value,
        ]);

        User::create([
            'name' => 'roozmehr',
            'email' => 'roozmehrknight@gmail.com',
            'password' => Hash::make('12341234'),
            'level' => UserLevelEnum::Admin->value,
        ]);
    }
}
