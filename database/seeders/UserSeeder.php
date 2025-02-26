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
            'name'=>'Felix Edward',
            'email'=>'felix@gmail.com',
            'telp'=>'08123408888',
            'role' => 'admin'
        ]);
        User::create([
            'username' => 'syarif',
            'password' => Hash::make('123'),
            'name'=>'Abdurrahman Syarif',
            'email'=>'sarip@gmail.com',
            'telp'=>'08127407778',
            'role' => 'admin'
        ]);

        // Make Ticketing
        User::create([
            'username' => 'ticket',
            'password' => Hash::make('password'),
            'name'=>'Caitlyn',
            'email'=>'cait@gmail.com',
            'telp'=>'081234015368',
            'role' => 'ticketing'
        ]);
        User::create([
            'username' => 'awik',
            'password' => Hash::make('123'),
            'name'=>'Awik',
            'email'=>'aw@gmail.com',
            'telp'=>'08123404881',
            'role' => 'ticketing'
        ]);
        User::create([
            'username' => 'josh',
            'password' => Hash::make('123'),
            'name'=>'Joshua Natan',
            'email'=>'josh@gmail.com',
            'telp'=>'08123408228',
            'role' => 'ticketing'
        ]);
    }
}
