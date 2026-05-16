<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TestSession; // <-- ADD THIS IMPORT
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('users.index', compact('users'));
    }

    // Show the registration form
    public function create()
    {
        return view('users.create');
    }

    // Handle the registration logic
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,researcher',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'User account created successfully.');
    }

    public function viewStaffSessions($id)
    {
        $staffMember = User::findOrFail($id);
        $sessions = TestSession::with(['participant', 'test'])
            ->where('user_id', $id) 
            ->latest()
            ->get();

        return view('users.sessions', compact('staffMember', 'sessions'));
    }
}