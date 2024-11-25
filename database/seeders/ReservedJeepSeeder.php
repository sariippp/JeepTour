<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservedJeepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reserve_jeep')->insert([
            [
                'reservation_id' => 1,
                'jeep_id' => 1,
            ],
            [
                'reservation_id' => 2,
                'jeep_id' => 2,
            ],
            [
                'reservation_id' => 3,
                'jeep_id' => 3,
            ]
        ]);
    }
}
