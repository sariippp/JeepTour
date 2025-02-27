<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use App\Models\Invoice;

class AdminProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        return view('admin.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'telp' => 'required|string|max:255',
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|string|min:6|confirmed';
        }

        $validatedData = $request->validate($rules);

        $user = auth()->user();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->telp = $request->telp;

        if ($request->filled('new_password')) {
            if (!Auth::attempt(['id' => $user->id, 'password' => $request->current_password])) {
                return back()->withErrors([
                    'current_password' => 'The current password is incorrect.'
                ])->withInput();
            }

            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }
}