<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@voxara.local'],
            [
                'name' => 'Admin VOXARA',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'demo@voxara.local'],
            [
                'name' => 'Pengguna Demo',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
