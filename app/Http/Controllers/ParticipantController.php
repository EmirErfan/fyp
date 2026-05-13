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
        $researcherId = $request->input('researcher_id');

        $searchId = $search;
        if (preg_match('/^#?P?-?0*(\d+)$/i', $search, $matches)) {
            $searchId = $matches[1]; 
        }

        $query = \App\Models\Participant::with('researcher');

        if (auth()->user()->role === 'researcher') {
            $query->where('user_id', auth()->id());
        } elseif ($researcherId) {
            $query->where('user_id', $researcherId);
        }

        $participants = $query->when($search, function ($q) use ($search, $searchId) {
            $q->where(function($subQ) use ($search, $searchId) {
                $subQ->where('name', 'like', "%{$search}%")
                     ->orWhere('id', 'like', "%{$searchId}%");
            });
        })
        ->orderBy('name', 'asc')
        ->get();

        return view('participants.index', compact('participants', 'researcherId'));
    }

    public function create()
    {
        $researchers = auth()->user()->role === 'admin' ? \App\Models\User::all() : collect();
        return view('participants.create', compact('researchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date', 
            'gender' => 'required|string',
            'date_joined' => 'required|date',
            'user_id' => 'nullable|exists:users,id'
        ]);

        Participant::create([
            'name' => $request->name,
            'dob' => $request->dob, 
            'gender' => $request->gender,
            'date_joined' => $request->date_joined,
            'user_id' => auth()->user()->role === 'admin' ? $request->user_id : auth()->id(),
        ]);

        return redirect('/participants')->with('success', 'Participant added successfully!');
    }

    public function edit($id)
    {
        $participant = Participant::findOrFail($id);
        
        if (auth()->user()->role === 'researcher' && $participant->user_id !== auth()->id()) {
            abort(403);
        }

        $researchers = auth()->user()->role === 'admin' ? \App\Models\User::all() : collect();
        return view('participants.edit', compact('participant', 'researchers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date', 
            'gender' => 'required|string',
            'date_joined' => 'required|date',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $participant = Participant::findOrFail($id);

        if (auth()->user()->role === 'researcher' && $participant->user_id !== auth()->id()) {
            abort(403);
        }

        $participant->update([
            'name' => $request->name,
            'dob' => $request->dob, 
            'gender' => $request->gender,
            'date_joined' => $request->date_joined,
            'user_id' => auth()->user()->role === 'admin' ? $request->user_id : $participant->user_id,
        ]);

        return redirect('/participants')->with('success', 'Participant updated successfully!');
    }

    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);

        if (auth()->user()->role === 'researcher' && $participant->user_id !== auth()->id()) {
            abort(403);
        }

        $participant->delete();
        return redirect('/participants')->with('success', 'Participant deleted successfully!');
    }
}