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
            ->leftJoin('reserve_jeep', 'reservations.id', '=', 'reserve_jeep.reservation_id')
            ->select(
                'reservations.id as reservation_id',
                'reservations.session_id as session_id',
                'reservations.name as name',
                'reservations.city as city',
                'sessions.date as date',
                'reservations.count as passenger_count',
                'sessions.session_time as session_hour',
                DB::raw('CASE WHEN reserve_jeep.jeep_id IS NULL THEN 0 ELSE 1 END as is_plotted')
            )
            ->where('sessions.date', '>=', Carbon::today())
            ->orderBy('sessions.date', 'asc')
            ->orderBy('sessions.id', 'asc')
            ->get();

        $jeepSlots = [];

        foreach ($orders as $order) {
            $jeeps = DB::table('jeeps')
                ->join('owners', 'owners.id', '=', 'jeeps.owner_id')
                ->leftJoin(DB::raw('(
                SELECT reserve_jeep.jeep_id, SUM(reservations.count) as used_capacity
                FROM reserve_jeep
                JOIN reservations ON reserve_jeep.reservation_id = reservations.id
                JOIN sessions ON reservations.session_id = sessions.id
                WHERE sessions.date = ? AND sessions.id = ?
                GROUP BY reserve_jeep.jeep_id
            ) as booked'), function ($join) {
                    $join->on('booked.jeep_id', '=', 'jeeps.id');
                })
                ->leftJoin(DB::raw('(
                SELECT reserve_jeep.jeep_id, reserve_jeep.reservation_id
                FROM reserve_jeep
                JOIN reservations ON reserve_jeep.reservation_id = reservations.id
                JOIN sessions ON reservations.session_id = sessions.id
                WHERE sessions.date = ? AND sessions.id = ?
            ) as reserved'), function ($join) {
                    $join->on('reserved.jeep_id', '=', 'jeeps.id');
                })
                ->setBindings([$order->date, $order->session_id, $order->date, $order->session_id])
                ->selectRaw('
                jeeps.id as jeep_id, 
                jeeps.number_plate, 
                owners.name as owner_name, 
                jeeps.total_passenger, 
                IFNULL(booked.used_capacity, 0) as used_capacity,
                jeeps.total_passenger - IFNULL(booked.used_capacity, 0) as slots_left,
                reserved.reservation_id as reserved_for
            ')
                ->orderBy('slots_left', 'desc')
                ->get();

            $selectedJeep = DB::table('reserve_jeep')
                ->where('reservation_id', $order->reservation_id)
                ->first();

            foreach ($jeeps as $jeep) {
                $jeep->is_selected = $selectedJeep && $selectedJeep->jeep_id == $jeep->jeep_id;
                $jeep->is_reserved_by_other = $jeep->reserved_for && $jeep->reserved_for != $order->reservation_id;
            }

            $jeepSlots[$order->reservation_id] = $jeeps;
        }

        $sessions = DB::table('sessions')
            ->join('reservations', 'sessions.id', '=', 'reservations.session_id')
            ->select('sessions.date as date', 'sessions.session_time as session_hour')
            ->where('sessions.date', '>=', Carbon::today())
            ->distinct()
            ->orderBy('sessions.session_time', 'asc')
            ->get();

        $datesForward = DB::table('sessions')
            ->join('reservations', 'sessions.id', '=', 'reservations.session_id')
            ->selectRaw(
                'sessions.date as full_date, 
            DAY(sessions.date) as day_group, 
            MONTHNAME(sessions.date) as month_group, 
            YEAR(sessions.date) as year_group'
            )
            ->where('sessions.date', '>=', Carbon::today())
            ->distinct()
            ->orderBy('sessions.date', 'asc')
            ->get();

        return view('ticketing.index', compact('orders', 'datesForward', 'sessions', 'jeepSlots'));
    }

    public function checkNewOrders(Request $request)
    {
        $lastCheck = $request->input('last_check', 0);

        $newOrdersCount = DB::table('reservations')
            ->where('created_at', '>', date('Y-m-d H:i:s', $lastCheck))
            ->count();

        return response()->json([
            'new_orders' => $newOrdersCount,
            'current_time' => time()
        ]);
    }

    public function invoiceIndex(Request $request)
    {
        $query = DB::table('reservations')
            ->leftJoin('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->select(
                'reservations.id',
                'reservations.name',
                'reservations.city',
                'reservations.count as passenger_count',
                'reservations.price',
                'reservations.payment_status',
                'reservations.created_at',
                'sessions.date as session_date',
                'sessions.session_time'
            );

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('reservations.id', 'like', "%{$searchTerm}%")
                    ->orWhere('reservations.name', 'like', "%{$searchTerm}%");
            });
        }

        $invoices = $query->orderBy('reservations.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('ticketing.invoices.table', compact('invoices'));
        }

        return view('ticketing.invoices.index', compact('invoices'));
    }



    public function savePlotting(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'selected_jeep' => 'required|exists:jeeps,id',
            'session_id' => 'required|exists:sessions,id',
            'date' => 'required|date',
        ]);

        $reservationId = $request->input('reservation_id');
        $jeepId = $request->input('selected_jeep');
        $sessionId = $request->input('session_id');
        $date = $request->input('date');

        $conflictingReservation = DB::table('reserve_jeep')
            ->join('reservations', 'reserve_jeep.reservation_id', '=', 'reservations.id')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->where('reserve_jeep.jeep_id', $jeepId)
            ->where('sessions.date', $date)
            ->where('sessions.id', $sessionId)
            ->where('reserve_jeep.reservation_id', '!=', $reservationId)
            ->first();

        if ($conflictingReservation) {
            return redirect()->route('ticketing.index')->with('error', 'Jeep sudah direservasi oleh pelanggan lain!');
        }

        $existingPlotting = DB::table('reserve_jeep')
            ->where('reservation_id', $reservationId)
            ->first();

        if ($existingPlotting) {
            DB::table('reserve_jeep')
                ->where('reservation_id', $reservationId)
                ->update([
                    'jeep_id' => $jeepId,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('reserve_jeep')->insert([
                'reservation_id' => $reservationId,
                'jeep_id' => $jeepId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('ticketing.index')->with('success', 'Jeep plotting berhasil disimpan!');
    }
}