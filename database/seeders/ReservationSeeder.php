<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::create([
            'session_id' => 1,
            'name' => 'Maya',
            'city' => 'Ende',
            'count' => 3,
            'price' => 45000,
            'date' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d') 
        ]);
        Reservation::create([
            'session_id' => 3,
            'name' => 'Syarif',
            'city' => 'Surabaya',
            'count' => 5,
            'price' => 45000,
            'date' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d') 
        ]);
        Reservation::create([
            'session_id' => 1,
            'name' => 'Awik',
            'city' => 'Blitar',
            'count' => 4,
            'price' => 45000,
            'date' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d') 
        ]);
        Reservation::create([
            'session_id' => 2,
            'name' => 'Ica',
            'city' => 'Sidoarjo',
            'count' => 1,
            'price' => 45000,
            'date' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d') 
        ]);
        Reservation::create([
            'session_id' => 3,
            'name' => 'Nana',
            'city' => 'Surabaya',
            'count' => 2,
            'price' => 45000,
            'date' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d') 
        ]);
    }
}
