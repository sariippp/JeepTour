<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        //perlu di benerin lagi kalau invoice timepaidnya not null sementara biarin dulu
        $totalPengunjung = Reservation::sum('count');

        $totalPendapatan = Reservation::all()->sum(function ($reservation) {
            return $reservation->count * $reservation->price;
        });

        $recentOrders = Reservation::orderBy('date', 'desc')->limit(5)->get();

        return view('admin.index', compact('totalPengunjung', 'totalPendapatan', 'recentOrders'));
    }
}
