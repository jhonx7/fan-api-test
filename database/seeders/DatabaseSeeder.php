<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::insert([
            [
                'id' => 1,
                'name' => 'Supervisor 1',
                'email' => 'supervisor1@test.com',
                'npp' => 111111,
                'npp_supervisor' => null,
                'password' => bcrypt('password'),
            ],
            [
                'id' => 2,
                'name' => 'Supervisor 2',
                'email' => 'supervisor2@test.com',
                'npp' => 222222,
                'npp_supervisor' => null,
                'password' => bcrypt('password'),
            ],
            [
                'id' => 3,
                'name' => 'User 1',
                'email' => 'user1@test.com',
                'npp' => 333333,
                'npp_supervisor' => 111111,
                'password' => bcrypt('password'),
            ],
            [
                'id' => 4,
                'name' => 'User 2',
                'email' => 'user2@test.com',
                'npp' => 444444,
                'npp_supervisor' => 222222,
                'password' => bcrypt('password'),
            ],
        ]);
    }
}
