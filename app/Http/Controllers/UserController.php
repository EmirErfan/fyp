<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TestSession;
use Illuminate\Support\Facades\Hash;
use App\Models\AccessRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResearcherAccessMail;

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

    public function accessRequests(){
        $requests = AccessRequest::where('status', 'pending')->latest()->get();
        return view('admin.requests', compact('requests'));
    }

    public function approveRequest($id){
        $request = AccessRequest::findOrFail($id);
        $password = Str::random(12);

        // Create user account
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'researcher',
        ]);

        $request->update(['status' => 'approved']);

        Mail::to($request->email)->send(new ResearcherAccessMail($request->email, $password));

        return back()->with('success', 'Account created and login information emailed');
    }

    public function showRequest($id)
    {
        $accessRequest = AccessRequest::findOrFail($id);
        return view('admin.show_request', compact('accessRequest'));
    }
}