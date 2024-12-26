<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Models\Jeep;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        $totalPengunjung = Reservation::sum('count');
        $totalPendapatan = Reservation::all()->sum(function ($reservation) {
            return $reservation->count * $reservation->price;
        });
        $recentOrders = Reservation::orderBy('date', 'desc')->limit(5)->get();

        return view('admin.index', compact('totalPengunjung', 'totalPendapatan', 'recentOrders'));
    }

    // Users Methods
    public function showUsers()
    {
        $loggedInUserId = Auth::id();
        $users = User::where('id', '!=', $loggedInUserId)->get();
        $groupedUsers = $users->groupBy('role')->sortByDesc(function ($users, $role) {
            return $role === 'admin' ? 1 : 0; 
        });

        return view('admin.user', compact('groupedUsers'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        // $user->email = $request->input('email');
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
    }

    // Financial Management Methods
    public function financialDashboard()
    {
        $stats = $this->getFinancialStats();
        $recentInvoices = Invoice::with(['reservation'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        $monthlyRevenue = Invoice::whereMonth('time_paid', Carbon::now()->month)
            ->whereYear('time_paid', Carbon::now()->year)
            ->sum('total');

        return view('admin.financial.financial', compact('stats', 'recentInvoices', 'monthlyRevenue'));
    }

    public function exportToExcel()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
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

        return view('admin.financial.invoices', compact('invoices'));
    }

    private function getFinancialStats()
    {
        return [
            'today_revenue' => Invoice::whereDate('created_at', today())->sum('total'),
            'month_revenue' => Invoice::whereMonth('created_at', now()->month)->sum('total'),
            'year_revenue' => Invoice::whereYear('created_at', now()->year)->sum('total'),
            'pending_payments' => Invoice::whereNull('time_paid')->count(),
            'average_booking_value' => Invoice::avg('total'),
            'total_revenue' => Invoice::sum('total')
        ];
    }

    public function generateFinancialReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $report = [
            'revenue' => Invoice::whereBetween('created_at', [$validated['start_date'], $validated['end_date']])
                ->sum('total'),
            'bookings' => Reservation::whereBetween('created_at', [$validated['start_date'], $validated['end_date']])
                ->count(),
            'active_jeeps' => Jeep::whereBetween('created_at', [$validated['start_date'], $validated['end_date']])
                ->count(),
            'daily_stats' => Invoice::whereBetween('created_at', [$validated['start_date'], $validated['end_date']])
                ->selectRaw('DATE(created_at) as date, SUM(total) as daily_total, COUNT(*) as invoice_count')
                ->groupBy('date')
                ->get()
        ];

        return view('admin.financial.report', compact('report'));
    }

    // Jeep Management Methods
    public function jeepManagement()
    {
        $currentMonth = date('Y-m');
        
        $ownerData = DB::select("
            SELECT 
                o.id, 
                o.name,
                COUNT(DISTINCT j.id) as total_jeeps,
                COALESCE(SUM(r.count), 0) as total_passengers,
                COALESCE(SUM(CASE WHEN DATE_FORMAT(r.created_at, '%Y-%m') = ? THEN r.count ELSE 0 END), 0) as monthly_passengers
            FROM owners o
            LEFT JOIN jeeps j ON o.id = j.owner_id
            LEFT JOIN reserve_jeep rj ON j.id = rj.jeep_id
            LEFT JOIN reservations r ON rj.reservation_id = r.id
            GROUP BY o.id, o.name", 
            [$currentMonth]
        );

        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        
        $jeeps = DB::select("
            SELECT 
                j.*,
                o.name as owner_name,
                COUNT(DISTINCT r.id) as total_trips,
                COALESCE(SUM(r.count), 0) as total_passengers
            FROM jeeps j
            JOIN owners o ON j.owner_id = o.id
            LEFT JOIN reserve_jeep rj ON j.id = rj.jeep_id
            LEFT JOIN reservations r ON rj.reservation_id = r.id
            GROUP BY j.id, j.number_plate, j.owner_id, o.name
        ");

        return view('admin.jeep.index', compact('ownerData', 'jeeps'));
    }

    public function storeOwner(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        DB::insert("INSERT INTO owners (name) VALUES (?)", [$request->name]);
        return response()->json(['success' => true]);
    }

    public function updateOwner(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        DB::update("UPDATE owners SET name = ? WHERE id = ?", [$request->name, $id]);
        return response()->json(['success' => true]);
    }

    public function deleteOwner($id)
    {
        DB::delete("DELETE FROM owners WHERE id = ?", [$id]);
        return response()->json(['success' => true]);
    }

    public function storeJeep(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'number_plate' => 'required|string|max:20|unique:jeeps'
        ]);

        DB::insert("INSERT INTO jeeps (owner_id, number_plate) VALUES (?, ?)", [
            $request->owner_id,
            $request->number_plate
        ]);
        return response()->json(['success' => true]);
    }

    public function updateJeep(Request $request, $id)
    {
        $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'number_plate' => 'required|string|max:20|unique:jeeps,number_plate,'.$id
        ]);

        DB::update("UPDATE jeeps SET owner_id = ?, number_plate = ? WHERE id = ?", [
            $request->owner_id,
            $request->number_plate,
            $id
        ]);
        return response()->json(['success' => true]);
    }

    public function deleteJeep($id)
    {
        DB::delete("DELETE FROM jeeps WHERE id = ?", [$id]);
        return response()->json(['success' => true]);
    }
}
