<?php

namespace App\Http\Controllers;

// These MUST be outside and above the class!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // Shows the edit profile page
    public function edit()
    {
        return view('profile.edit');
    }

    // Handles saving the profile data and image
    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB Image
            'university' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        // 2. Handle Profile Picture Upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if they are uploading a new one
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Save the new image
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $imagePath;
        }

        // 3. Save standard text fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->university = $request->university;
        $user->department = $request->department;
        $user->bio = $request->bio;
        
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // Shows the change password page
    public function changePassword()
    {
        return view('profile.password');
    }

    // Handles the password update
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}