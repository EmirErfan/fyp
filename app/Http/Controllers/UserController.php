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

    public function edit($id)
    {
        // Find the user or throw a 404 if they don't exist
        $editUser = User::findOrFail($id); 

        // Send the user data to a new view we are about to create
        return view('users.edit', compact('editUser'));
    }

    public function update(Request $request, $id)
    {
        // 1. Find the specific user being edited
        $editUser = User::findOrFail($id);

        // 2. Validate all the fields (including the Admin-only 'role' field)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $editUser->id,
            'role' => 'required|in:admin,researcher',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'university' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        // 3. Handle the Image Upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if it exists
            if ($editUser->profile_picture) {
                Storage::disk('public')->delete($editUser->profile_picture);
            }
            
            // Save new picture
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $editUser->profile_picture = $imagePath;
        }

        // 4. Save the rest of the text data
        $editUser->name = $request->name;
        $editUser->email = $request->email;
        $editUser->role = $request->role; // Admin can change roles
        $editUser->university = $request->university;
        $editUser->department = $request->department;
        $editUser->bio = $request->bio;
        
        $editUser->save();

        // 5. Send them back to the user list with a success message
        return redirect('/users')->with('success', 'User account updated successfully.');
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);

        // Security check: Prevent the admin from deleting themselves
        if (auth()->id() === $user->id) {
            return redirect('/users')->with('error', 'Action denied: You cannot delete your own administrator account.');
        }

        $user->delete();

        return redirect('/users')->with('success', 'User account has been permanently deleted.');
    }
}