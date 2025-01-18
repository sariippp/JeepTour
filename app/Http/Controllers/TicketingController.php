<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use App\Models\Invoice;

class TicketingController extends Controller
{
    public function index()
    {
        $orders = DB::table('reservations')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->select(
                'reservations.id as reservation_id',
                'reservations.name as name',
                'reservations.city as city',
                'reservations.date as date',
                'reservations.count as passenger_count',
                'sessions.session as session_hour'
            )
            ->where('reservations.date', '>=', Carbon::today())
            ->orderBy('reservations.date', 'asc')
            ->orderBy('sessions.id', 'asc')
            ->get();

        $sessions = DB::table('reservations')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->select('reservations.date as date','sessions.session as session_hour') // Select distinct session hour
            ->where('reservations.date', '>=', Carbon::today())
            ->distinct()
            ->orderBy('sessions.session', 'asc')
            ->get();


        $datesForward = DB::table('reservations')->selectRaw
        ('reservations.date as full_date,DAY(reservations.date) as day_group, 
        MONTHNAME(reservations.date) as month_group, 
        YEAR(reservations.date) as year_group')
            ->where('reservations.date', '>=', Carbon::today())->distinct()->orderBy('reservations.date', 'asc')
            ->get();


        return view('ticketing.index', compact('orders', 'datesForward', 'sessions'));
    }

    public function invoiceIndex(Request $request)
    {
        $query = Invoice::with(['reservation']);

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                ->orWhereHas('reservation', function ($reservationQuery) use ($searchTerm) {
                    $reservationQuery->where('id', 'like', "%{$searchTerm}%");
                });
            });
        }

        $invoices = $query->latest()->paginate(10);

        return view('ticketing.invoices.index', compact('invoices'));
    }


    // public function exportToExcel()
    // {
    //     return Excel::download(new InvoicesExport, 'invoices.xlsx');
    // }
}
