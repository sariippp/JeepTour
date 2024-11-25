<?php

namespace Database\Seeders;

use App\Models\Jeep;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JeepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jeep::create([
            'owner_id' => 1,
            'number_plate' => 'L 1234 AB',
            'total_passenger' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Jeep::create([
            'owner_id' => 1,
            'number_plate' => 'L 5678 CD',
            'total_passenger' => 6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Jeep::create([
            'owner_id' => 2,
            'number_plate' => 'L 3234 QS',
            'total_passenger' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Jeep::create([
            'owner_id' => 3,
            'number_plate' => 'L 1234 AH',
            'total_passenger' => 6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
