<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@artcraft.com'],
            [
                'name' => 'Administrador Art Craft',
                'password' => Hash::make('admin12345'),
                'rol' => 'admin',
            ],
        );
    }
}
