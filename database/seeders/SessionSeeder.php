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
        for ($hour = 9; $hour <= 17; $hour++) {
            $sessionTime = sprintf('%02d:00:00', $hour);
            
            Session::create([
                'session' => $sessionTime,
                'passenger_count' => 24
            ]);
        }
    }
}
