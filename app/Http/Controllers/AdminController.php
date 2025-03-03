<?php

namespace App\Http\Controllers;

use App\Models\Jeep;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        $totalPengunjung = Reservation::sum('count');
        $totalPendapatan = Reservation::all()->sum(function ($reservation) {
            return $reservation->count * $reservation->price;
        });
        $recentOrders = Reservation::with('session')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->select('reservations.*', 'sessions.date', 'sessions.session_time')
            ->orderBy('sessions.date', 'desc')
            ->limit(5)
            ->get();

        return view('admin.index', compact('totalPengunjung', 'totalPendapatan', 'recentOrders'));
    }

    // Users
    public function showUsers()
    {
        $loggedInUserId = Auth::id();
        $users = User::where('id', '!=', $loggedInUserId)->get();
        $groupedUsers = $users->groupBy('role')->sortByDesc(function ($users, $role) {
            return $role === 'admin' ? 1 : 0;
        });

        return view('admin.user.index', compact('groupedUsers'));
    }

    public function storeUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:6',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'telp' => 'required|string|max:255',
                'role' => 'required|in:admin,ticketing',
            ]);

            $user = new User();
            $user->username = $validated['username'];
            $user->password = bcrypt($validated['password']); // Encrypting password
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->telp = $validated['telp'];
            $user->role = $validated['role'];
            $user->created_at = now();
            $user->updated_at = now();

            $success = $user->save();

            if ($request->ajax() || $request->wantsJson()) {
                if ($success) {
                    return response()->json([
                        'success' => true,
                        'message' => 'User created successfully',
                        'user' => $user
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create user'
                    ], 500);
                }
            }

            if ($success) {
                return redirect()->route('admin.users')->with('success', 'User created successfully');
            } else {
                return redirect()->route('admin.users')->with('error', 'Failed to create user');
            }

        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating user: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->route('admin.users')
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
    }

    // Financial Management
    public function financialDashboard()
    {
        $stats = $this->getFinancialStats();
        $recentReservations = Reservation::with('session')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->select('reservations.*', 'sessions.date', 'sessions.session_time')
            ->whereDate('sessions.date', Carbon::today())
            ->orderBy('reservations.created_at', 'desc')
            ->limit(5)
            ->get();

        $monthlyRevenue = DB::table('reservations')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->whereMonth('sessions.date', Carbon::now()->month)
            ->whereYear('sessions.date', Carbon::now()->year)
            ->where('reservations.payment_status', 'paid')
            ->sum(DB::raw('reservations.price * reservations.count'));

        return view('admin.financial.financial', compact('stats', 'recentReservations', 'monthlyRevenue'));
    }

    public function exportToExcel()
    {
        return Excel::download(new class implements FromCollection, WithHeadings, WithMapping {
            public function collection()
            {
                return Reservation::with('session')->get();
            }

            public function headings(): array
            {
                return [
                    'ID',
                    'Customer Name',
                    'City',
                    'Passengers',
                    'Price',
                    'Total',
                    'Date',
                    'Session Time',
                    'Payment Status',
                    'Created At',
                ];
            }

            public function map($reservation): array
            {
                return [
                    $reservation->id,
                    $reservation->name,
                    $reservation->city,
                    $reservation->count,
                    $reservation->price,
                    $reservation->price * $reservation->count,
                    $reservation->session ? Carbon::parse($reservation->session->date)->format('Y-m-d') : 'N/A',
                    $reservation->session ? $reservation->session->session_time : 'N/A',
                    ucfirst($reservation->payment_status),
                    $reservation->created_at ? Carbon::parse($reservation->created_at)->format('Y-m-d H:i:s') : 'N/A',
                ];
            }
        }, 'reservations.xlsx');
    }


    // Update the getIncomeData method in AdminController.php to support year filtering

    public function getIncomeData(Request $request)
    {
        $months = $request->input('months', 3); // Default to 3 months
        $year = $request->input('year', 'all'); // Default to all years

        // Get the end date (current month)
        $endDate = Carbon::now()->endOfMonth();

        // Get the start date (X months ago)
        $startDate = Carbon::now()->subMonths($months - 1)->startOfMonth();

        // Build the base query
        $query = DB::table('reservations')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->where('reservations.payment_status', 'paid');

        // Apply year filter if specified
        if ($year !== 'all') {
            $query->whereYear('sessions.date', $year);
        } else {
            // If no year specified, use the date range
            $query->whereDate('sessions.date', '>=', $startDate)
                ->whereDate('sessions.date', '<=', $endDate);
        }

        // Get monthly data
        $monthlyData = $query->select(
            DB::raw('YEAR(sessions.date) as year'),
            DB::raw('MONTH(sessions.date) as month'),
            DB::raw('SUM(reservations.price * reservations.count) as revenue')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format the data for the chart
        $formattedData = [];

        // Create a date period based on filter type
        if ($year !== 'all') {
            // For year filter, include all 12 months of the specified year
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfMonth();

            // When a specific year is selected, we ignore the months parameter
            // and always show the full year
        }

        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1M'),
            $endDate
        );

        // Indonesian month names
        $indonesianMonths = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Initialize the array with all months in the range (including those with zero revenue)
        foreach ($period as $date) {
            $yearValue = $date->format('Y');
            $month = (int) $date->format('m');

            $formattedData[] = [
                'month' => $indonesianMonths[$month] . ' ' . $yearValue,
                'revenue' => 0,
                'year' => $yearValue,
                'month_num' => $month
            ];
        }

        // Add the current month if not already included and we're not filtering by year
        if ($year === 'all') {
            $currentYear = $endDate->format('Y');
            $currentMonth = (int) $endDate->format('m');
            $currentMonthIncluded = false;

            foreach ($formattedData as $data) {
                if ($data['year'] == $currentYear && $data['month_num'] == $currentMonth) {
                    $currentMonthIncluded = true;
                    break;
                }
            }

            if (!$currentMonthIncluded) {
                $formattedData[] = [
                    'month' => $indonesianMonths[$currentMonth] . ' ' . $currentYear,
                    'revenue' => 0,
                    'year' => $currentYear,
                    'month_num' => $currentMonth
                ];
            }
        }

        // Fill in the actual revenue data
        foreach ($monthlyData as $data) {
            foreach ($formattedData as &$item) {
                if ($item['year'] == $data->year && $item['month_num'] == $data->month) {
                    $item['revenue'] = (float) $data->revenue;
                    break;
                }
            }
        }

        // Sort by year and month
        usort($formattedData, function ($a, $b) {
            if ($a['year'] !== $b['year']) {
                return $a['year'] <=> $b['year'];
            }
            return $a['month_num'] <=> $b['month_num'];
        });

        // Remove helper fields not needed for the chart
        $formattedData = array_map(function ($item) {
            return [
                'month' => $item['month'],
                'revenue' => $item['revenue']
            ];
        }, $formattedData);

        return response()->json($formattedData);
    }

    // The getAvailableYears method already exists in your code, so no need to add it

    public function invoiceIndex(Request $request)
    {
        $query = Reservation::with('session');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                    ->orWhere('name', 'like', "%{$searchTerm}%")
                    ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.financial.invoices', compact('reservations'));
    }

    private function getFinancialStats()
    {
        return [
            'today_revenue' => DB::table('reservations')
                ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
                ->whereDate('sessions.date', today())
                ->where('reservations.payment_status', 'paid')
                ->sum(DB::raw('reservations.price * reservations.count')),

            'month_revenue' => DB::table('reservations')
                ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
                ->whereMonth('sessions.date', now()->month)
                ->where('reservations.payment_status', 'paid')
                ->sum(DB::raw('reservations.price * reservations.count')),

            'year_revenue' => DB::table('reservations')
                ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
                ->whereYear('sessions.date', now()->year)
                ->where('reservations.payment_status', 'paid')
                ->sum(DB::raw('reservations.price * reservations.count')),

            'pending_payments' => Reservation::where('payment_status', 'pending')->count(),

            'average_booking_value' => Reservation::where('payment_status', 'paid')
                ->avg(DB::raw('price * count')),

            'total_revenue' => Reservation::where('payment_status', 'paid')
                ->sum(DB::raw('price * count'))
        ];
    }

    public function generateFinancialReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $report = [
            'revenue' => DB::table('reservations')
                ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
                ->whereBetween('sessions.date', [$validated['start_date'], $validated['end_date']])
                ->where('reservations.payment_status', 'paid')
                ->sum(DB::raw('reservations.price * reservations.count')),

            'bookings' => DB::table('reservations')
                ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
                ->whereBetween('sessions.date', [$validated['start_date'], $validated['end_date']])
                ->count(),

            'active_jeeps' => Jeep::count(),

            'daily_stats' => DB::table('reservations')
                ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
                ->whereBetween('sessions.date', [$validated['start_date'], $validated['end_date']])
                ->where('reservations.payment_status', 'paid')
                ->selectRaw('sessions.date as date, SUM(reservations.price * reservations.count) as daily_total, COUNT(*) as reservation_count')
                ->groupBy('sessions.date')
                ->get()
        ];

        return view('admin.financial.report', compact('report'));
    }

    // Jeep Management
    public function jeepManagement()
    {
        $currentMonth = date('Y-m');

        $ownerData = DB::select("
            SELECT 
                o.id, 
                o.name,
                COUNT(DISTINCT j.id) as total_jeeps,
                COALESCE(SUM(r.count), 0) as total_passengers,
                COALESCE(SUM(CASE WHEN DATE_FORMAT(s.date, '%Y-%m') = ? THEN r.count ELSE 0 END), 0) as monthly_passengers
            FROM owners o
            LEFT JOIN jeeps j ON o.id = j.owner_id
            LEFT JOIN reserve_jeep rj ON j.id = rj.jeep_id
            LEFT JOIN reservations r ON rj.reservation_id = r.id
            LEFT JOIN sessions s ON r.session_id = s.id
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

        $owner = new Owner();
        $owner->name = $request->name;
        $owner->save();

        return response()->json(['success' => true]);
    }

    public function updateOwner(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->save();

        return response()->json(['success' => true]);
    }

    public function deleteOwner($id)
    {
        $owner = Owner::findOrFail($id);
        $owner->delete();

        return response()->json(['success' => true]);
    }

    public function storeJeep(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'number_plate' => 'required|string|max:20|unique:jeeps',
            'total_passenger' => 'required|integer|min:1'
        ]);

        $jeep = new Jeep();
        $jeep->owner_id = $request->owner_id;
        $jeep->number_plate = $request->number_plate;
        $jeep->total_passenger = $request->total_passenger;
        $jeep->save();

        return response()->json(['success' => true]);
    }

    public function updateJeep(Request $request, $id)
    {
        $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'number_plate' => 'required|string|max:20|unique:jeeps,number_plate,' . $id,
            'total_passenger' => 'required|integer|min:1'
        ]);

        $jeep = Jeep::findOrFail($id);
        $jeep->owner_id = $request->owner_id;
        $jeep->number_plate = $request->number_plate;
        $jeep->total_passenger = $request->total_passenger;
        $jeep->save();

        return response()->json(['success' => true]);
    }

    public function getCityDistribution()
    {
        $cityDistribution = DB::table('reservations')
            ->select(DB::raw('LOWER(city) as city_lower, city, SUM(count) as count'))
            ->groupBy('city_lower', 'city')
            ->orderBy('count', 'desc')
            ->get();

        $formattedData = $cityDistribution->map(function ($item) {
            return [
                'city' => $item->city,
                'count' => (int) $item->count
            ];
        });

        return response()->json($formattedData);
    }

    public function getMonthlyRevenue(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Create date range for the selected month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $revenue = DB::table('reservations')
            ->join('sessions', 'reservations.session_id', '=', 'sessions.id')
            ->whereYear('sessions.date', $year)
            ->whereMonth('sessions.date', $month)
            ->whereDate('sessions.date', '>=', $startDate)
            ->whereDate('sessions.date', '<=', $endDate)
            ->where('reservations.payment_status', 'paid')
            ->sum(DB::raw('reservations.price * reservations.count'));

        return response()->json(['revenue' => $revenue]);
    }

    public function getAvailableYears()
    {
        $years = DB::select('SELECT DISTINCT YEAR(date) as year FROM sessions ORDER BY year DESC');

        $yearArray = collect($years)->map(function ($item) {
            return (string) $item->year;
        })->toArray();

        return response()->json(['years' => $yearArray]);
    }

    public function deleteJeep($id)
    {
        $jeep = Jeep::findOrFail($id);
        $jeep->delete();

        return response()->json(['success' => true]);
    }
}