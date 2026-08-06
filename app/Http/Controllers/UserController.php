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
use App\Mail\ResearcherAccessRejectedMail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('team');
        $sort = $request->get('sort', 'role');
        $dir = $request->get('dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $teamFilter = $request->get('team_id');

        if ($teamFilter) {
            $query->where('team_id', $teamFilter);
        }

        if ($sort === 'role') {
            $query->orderBy('role', $dir);
        } elseif ($sort === 'team') {
            $query->leftJoin('teams', 'users.team_id', '=', 'teams.id')
                  ->orderBy('teams.name', $dir)
                  ->select('users.*');
        } else {
            $query->orderBy('users.id', $dir);
        }

        $users = $query->paginate(5)->withQueryString();
        $teams = \App\Models\Team::orderBy('name')->get();
        return view('users.index', compact('users', 'teams'));
    }

    // Show the registration form
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,researcher',
            'institution' => 'nullable|string|max:255',
            'purpose' => 'nullable|string',
        ]);

        $password = Str::random(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'university' => $request->institution,
            'bio' => $request->purpose,
            'must_change_password' => true,
        ]);

        if ($request->role === 'admin') {
            $team = \App\Models\Team::create(['name' => $request->name . "'s Team"]);
            $user->update(['team_id' => $team->id]);
        } else {
            $user->update(['team_id' => auth()->user()->team_id]);
        }

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ResearcherAccessMail($user->email, $password));

        return redirect('/users')->with('success', 'User account created successfully. Login credentials have been emailed.');
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
        $requests = AccessRequest::where('status', 'pending')
                        ->where('head_researcher_email', auth()->user()->email)
                        ->latest()->get();
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
            'team_id' => auth()->user()->team_id,
            'must_change_password' => true,
        ]);

        $request->update(['status' => 'approved']);

        Mail::to($request->email)->send(new ResearcherAccessMail($request->email, $password));

        return back()->with('success', 'Account created and login information emailed');
    }

    public function rejectRequest($id){
        $request = AccessRequest::findOrFail($id);
        
        $request->update(['status' => 'rejected']);

        Mail::to($request->email)->send(new ResearcherAccessRejectedMail($request->name));

        return back()->with('success', 'Request rejected and notification emailed.');
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
        $teams = \App\Models\Team::orderBy('name')->get();

        // Send the user data to a new view we are about to create
        return view('users.edit', compact('editUser', 'teams'));
    }

    public function update(Request $request, $id)
    {
        // 1. Find the specific user being edited
        $editUser = User::findOrFail($id);

        // 2. Validate all the fields (including the Admin-only 'role' field)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $editUser->id,
            'team_id' => 'nullable|exists:teams,id',
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
        
        // Handle team change for researchers
        if ($editUser->role === 'researcher' && $request->has('team_id') && $editUser->team_id != $request->team_id) {
            \App\Models\Participant::where('user_id', $editUser->id)->update(['team_id' => $request->team_id]);
            $editUser->team_id = $request->team_id;
        }

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

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ResearcherRemovedMail($user->name));

        $user->delete();

        return redirect('/users')->with('success', 'User account has been permanently deleted.');
    }
}