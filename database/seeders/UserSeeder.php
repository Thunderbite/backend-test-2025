<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        User::create([
            'name' => 'Test',
            'email' => 'test@thunderbite.com',
            'level' => 'admin',
            'password' => Hash::make('test123'),
        ]);
    }
}
