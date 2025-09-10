<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // email to identify
            [
                'name' => 'Admin',
                'password' => Hash::make('password'), // change to a strong password
                'is_admin' => true
            ]
        );
    }
}
