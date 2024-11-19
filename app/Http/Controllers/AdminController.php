<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
