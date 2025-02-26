<?php

namespace Database\Seeders;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Session::create([
            'date' => '2025-03-01',
            'session_time' => '09:00',
            'passenger_count' => 24,
        ]);
        Session::create([
            'date' => '2025-03-01',
            'session_time' => '10:00',
            'passenger_count' => 24,
        ]);
        Session::create([
            'date' => '2025-03-01',
            'session_time' => '11:00',
            'passenger_count' => 24,
        ]);
        Session::create([
            'date' => '2025-03-02',
            'session_time' => '09:00',
            'passenger_count' => 24,
        ]);
        Session::create([
            'date' => '2025-03-02',
            'session_time' => '10:00',
            'passenger_count' => 24,
        ]);
        Session::create([
            'date' => '2025-03-02',
            'session_time' => '11:00',
            'passenger_count' => 24,
        ]);
    }
}
