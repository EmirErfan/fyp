<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant; // Import the Participant Model!

class ParticipantController extends Controller
{
    // Shows the list of participants
    public function index()
    {
        // Get all participants from the database
        $participants = Participant::all(); 
        
        return view('participants.index', compact('participants'));
    }

    // Shows the blank form
    public function create()
    {
        return view('participants.create');
    }

    // Saves the new participant to the database
    public function store(Request $request)
    {
        // 1. Validate the form data
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'date_joined' => 'required|date',
        ]);

        // 2. Create the record in the database
        Participant::create([
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'date_joined' => $request->date_joined,
        ]);

        // 3. Send them back to the list
        return redirect('/participants')->with('success', 'Participant added successfully!');
    }
}