<?php

namespace Database\Seeders;

use App\Models\Owner;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Owner::create([
            'name' => 'Owner A',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        Owner::create([
            'name' => 'Owner B',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        Owner::create([
            'name' => 'Owner C',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
    }
}
