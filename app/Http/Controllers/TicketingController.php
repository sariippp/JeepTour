<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketingController extends Controller
{
    public function index()
    {
        return view('ticketing.index');
    }

    public function orderLog(){
        return view('ticketing.order');
        // belum ada halamannya
    }
}
