<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessRequest;

class AccessRequestController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'institution' => 'required|string|max:255',
            'purpose' => 'required|string',
            'head_researcher_email' => 'required|email|exists:users,email',
        ], [
            'head_researcher_email.exists' => 'The Head Researcher email provided does not exist in our system.'
        ]);

        // Check if head researcher is actually an admin
        $headResearcher = \App\Models\User::where('email', $request->head_researcher_email)->where('role', 'admin')->first();
        if (!$headResearcher) {
            return back()->withErrors(['head_researcher_email' => 'The provided email belongs to a user who is not a Head Researcher.']);
        }

        AccessRequest::create([
            'name' => $request->name,
            'email' => $request->email,
            'institution' => $request->institution,
            'purpose' => $request->purpose,
            'head_researcher_email' => $request->head_researcher_email,
        ]);

        return back()->with('success', 'Your request has been submitted');
    }
}
