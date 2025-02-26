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
            'email' =>'maya@gmail.com',
            'telp'=>'081111111112',
            'city' => 'Ende',
            'count' => 3,
            'price' => 45000,
            'payment_status' => 'paid' 
        ]);
        Reservation::create([
            'session_id' => 3,
            'name' => 'Syarif',
            'email' =>'syarif@gmail.com',
            'telp'=>'081113113112',
            'city' => 'Jakarta',
            'count' => 2,
            'price' => 45000,
            'payment_status' => 'paid' 
        ]);
        Reservation::create([
            'session_id' => 1,
            'name' => 'Awik',
            'email' =>'awik@gmail.com',
            'telp'=>'089161611112',
            'city' => 'Blitar',
            'count' => 1,
            'price' => 45000,
            'payment_status' => 'paid' 
        ]);
        Reservation::create([
            'session_id' => 2,
            'name' => 'Ica',
            'email' =>'ica@gmail.com',
            'telp'=>'089151671912',
            'city' => 'Manado',
            'count' => 6,
            'price' => 45000,
            'payment_status' => 'paid' 
        ]);
        Reservation::create([
            'session_id' => 5,
            'name' => 'Nicole',
            'email' =>'nicole@gmail.com',
            'telp'=>'089161611112',
            'city' => 'Surabaya',
            'count' => 2,
            'price' => 45000,
            'payment_status' => 'paid' 
        ]);
    }
}
