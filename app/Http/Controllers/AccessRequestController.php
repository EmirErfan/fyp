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
            'purpose' => 'required|string',
        ]);

        AccessRequest::create([
            'name' => $request->name,
            'email' => $request->email,
            'purpose' => $request->purpose,
        ]);

        return back()->with('success', 'Your request has been submitted');
    }
}
