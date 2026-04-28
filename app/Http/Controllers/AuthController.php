<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Shows the visual HTML Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handles the form submission
    public function login(Request $request)
    {
        // 1. Validate what the user typed in
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Check if the email and password match the database
        if (Auth::attempt($credentials)) {
            // Security feature: resets the session to prevent hacking
            $request->session()->regenerate(); 
            
            // THE FIX: Send them to the main system dashboard!
            return redirect('/dashboard'); 
        }

        // 3. If they typed the wrong password, send them back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handles logging out
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}