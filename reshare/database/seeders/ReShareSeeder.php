<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReShareSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@reshare.test'],
            [
                'nama' => 'Admin ReShare',
                'role' => 'admin',
                'password' => Hash::make('admin12345'),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'user@reshare.test'],
            [
                'nama' => 'User Demo',
                'role' => 'user',
                'password' => Hash::make('user12345'),
            ]
        );

        echo "Akun Admin dan User berhasil disiapkan!\n";
    }
}
