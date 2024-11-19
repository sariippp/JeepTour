<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        User::create([
            'username' => 'syarif',
            'password' => Hash::make('123'),
            'role' => 'admin'
        ]);

        // Make Ticketing
        User::create([
            'username' => 'ticket',
            'password' => Hash::make('password'),
            'role' => 'ticketing'
        ]);
        User::create([
            'username' => 'awik',
            'password' => Hash::make('123'),
            'role' => 'ticketing'
        ]);
        User::create([
            'username' => 'josh',
            'password' => Hash::make('123'),
            'role' => 'ticketing'
        ]);
    }
}
