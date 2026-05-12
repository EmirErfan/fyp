<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;

class ParticipantController extends Controller
{
    // 1. THE UPGRADED DASHBOARD (With Smart Search)
    public function index(Request $request)
    {
        $search = $request->input('search');

        // UPGRADED SMART ID SEARCH:
        $searchId = $search;
        if (preg_match('/^#?P?-?0*(\d+)$/i', $search, $matches)) {
            $searchId = $matches[1]; 
        }

        $participants = \App\Models\Participant::when($search, function ($query) use ($search, $searchId) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$searchId}%");
        })
        ->orderBy('name', 'asc')
        ->get();

        return view('participants.index', compact('participants'));
    }

    // 2. RESTORED: Shows the blank form when you click "Add Participant"
    public function create()
    {
        return view('participants.create');
    }

    // 3. RESTORED: Saves the new participant to the MariaDB database
    public function store(Request $request)
    {
        // 1. Validate 'dob' instead of 'age'
        $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date', 
            'gender' => 'required|string',
            'date_joined' => 'required|date',
        ]);

        // 2. Save 'dob' to the database
        Participant::create([
            'name' => $request->name,
            'dob' => $request->dob, 
            'gender' => $request->gender,
            'date_joined' => $request->date_joined,
        ]);

        return redirect('/participants')->with('success', 'Participant added successfully!');
    }

    public function edit($id)
    {
        $participant = Participant::findOrFail($id);
        return view('participants.edit', compact('participant'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date', 
            'gender' => 'required|string',
            'date_joined' => 'required|date',
        ]);

        $participant = Participant::findOrFail($id);
        $participant->update([
            'name' => $request->name,
            'dob' => $request->dob, 
            'gender' => $request->gender,
            'date_joined' => $request->date_joined,
        ]);

        return redirect('/participants')->with('success', 'Participant updated successfully!');
    }
}