<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sipustaka.com'],
            [
                'name' => 'Admin SiPustaka',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@sipustaka.com'],
            [
                'name' => 'User SiPustaka',
                'password' => Hash::make('user12345'),
                'role' => 'user',
            ]
        );
    }
}