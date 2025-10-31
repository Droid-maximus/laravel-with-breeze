<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // unikālais identificētājs
            [
                'name' => 'Sistēmas administrators',
                'password' => Hash::make('secret123'), 
                'role' => 'admin',
            ]
        );
    }
}
