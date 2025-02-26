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

class TicketingProfileController extends Controller
{
    public function profile()
    {
        // Get the currently authenticated user
        $user = auth()->user();

        return view('ticketing.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Validate the request
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'telp' => 'required|string|max:255',
        ];

        // Add password validation rules if the user is trying to change password
        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|string|min:6|confirmed';
        }

        $validatedData = $request->validate($rules);

        // Get the current user
        $user = auth()->user();

        // Update user data
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->telp = $request->telp;

        // Update password if provided and current password is correct
        if ($request->filled('new_password')) {
            // Verify current password
            if (!Auth::attempt(['id' => $user->id, 'password' => $request->current_password])) {
                return back()->withErrors([
                    'current_password' => 'The current password is incorrect.'
                ])->withInput();
            }

            // Hash the new password using the same method as in LoginController
            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return redirect()->route('ticketing.profile')->with('success', 'Profile updated successfully!');
    }
}