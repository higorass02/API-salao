<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Alice Silva',
            'email' => 'alice@email.com',
            'password' => Hash::make('123456'),
            'role' => 1, // admin
            'status' => 1,
        ]);

        User::create([
            'name' => 'Bruno Souza',
            'email' => 'bruno@email.com',
            'password' => Hash::make('123456'),
            'role' => 2, // staff
            'status' => 1,
        ]);

        User::create([
            'name' => 'Carla Oliveira',
            'email' => 'carla@email.com',
            'password' => Hash::make('123456'),
            'role' => 3, // client
            'status' => 1,
        ]);
    }
}
