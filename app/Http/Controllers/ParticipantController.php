<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $researcherId = $request->input('researcher_id');

        $searchId = $search;
        if (preg_match('/^#?P?-?0*(\d+)$/i', $search, $matches)) {
            $searchId = $matches[1]; 
        }

        $query = \App\Models\Participant::with('researcher');

        if (auth()->user()->role === 'admin') {
            $query->where('team_id', auth()->user()->team_id);
            if ($researcherId) {
                $query->where('user_id', $researcherId);
            }
        } elseif (auth()->user()->role === 'researcher') {
            $query->where('user_id', auth()->id());
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
        $researchers = auth()->user()->role === 'admin' ? \App\Models\User::where('team_id', auth()->user()->team_id)->get() : collect();
        return view('participants.create', compact('researchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female',
            // Added unique validation for email
            'email' => 'nullable|email|max:255|unique:participants,email', 
            // Added unique validation for phone
            'phone' => ['nullable', 'regex:/^0[0-9]{9,10}$/', 'unique:participants,phone'],
            'occupation' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'color_blindness' => 'required|string',
            'existing_condition' => 'nullable|string',
        ], [
            'phone.regex' => 'The phone number must start with 0 and contain exactly 10 or 11 digits (e.g., 0123456789). No dashes or spaces allowed.',
            'email.email' => 'Please enter a valid email address.',
            // Added custom error messages
            'email.unique' => 'This email address is already registered to another participant.',
            'phone.unique' => 'This phone number is already registered to another participant.',
        ]);

        Participant::create([
            'name' => $request->name,
            'dob' => $request->dob, 
            'gender' => $request->gender,
            'date_joined' => now()->toDateString(),
            'user_id' => auth()->user()->role === 'admin' ? ($request->user_id ?? $request->researcher_id) : auth()->id(),
            'team_id' => auth()->user()->team_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'occupation' => $request->occupation,
            'education_level' => $request->education_level,
            'color_blindness' => $request->color_blindness,
            'existing_condition' => $request->existing_condition,
        ]);

        return redirect('/participants')->with('success', 'Participant added successfully!');
    }

    public function edit($id)
    {
        $participant = Participant::findOrFail($id);
        
        if (auth()->user()->role === 'admin' && $participant->team_id !== auth()->user()->team_id) {
            abort(403);
        }
        if (auth()->user()->role === 'researcher' && $participant->user_id !== auth()->id()) {
            abort(403);
        }

        $researchers = auth()->user()->role === 'admin' ? \App\Models\User::where('team_id', auth()->user()->team_id)->get() : collect();
        return view('participants.edit', compact('participant', 'researchers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female',
            // Added unique validation, but appending the $id so it ignores the current participant
            'email' => 'nullable|email|max:255|unique:participants,email,' . $id, 
            // Added unique validation, but appending the $id so it ignores the current participant
            'phone' => ['nullable', 'regex:/^0[0-9]{9,10}$/', 'unique:participants,phone,' . $id],
            'occupation' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'color_blindness' => 'required|string',
            'existing_condition' => 'nullable|string',
        ], [
            'phone.regex' => 'The phone number must start with 0 and contain exactly 10 or 11 digits (e.g., 0123456789). No dashes or spaces allowed.',
            'email.email' => 'Please enter a valid email address.',
            // Added custom error messages
            'email.unique' => 'This email address is already registered to another participant.',
            'phone.unique' => 'This phone number is already registered to another participant.',
        ]);

        $participant = Participant::findOrFail($id);

        if (auth()->user()->role === 'admin' && $participant->team_id !== auth()->user()->team_id) {
            abort(403);
        }
        if (auth()->user()->role === 'researcher' && $participant->user_id !== auth()->id()) {
            abort(403);
        }

        $participant->update([
            'name' => $request->name,
            'dob' => $request->dob, 
            'gender' => $request->gender,
            'user_id' => auth()->user()->role === 'admin' ? ($request->user_id ?? $request->researcher_id ?? $participant->user_id) : $participant->user_id,
            'team_id' => auth()->user()->role === 'admin' ? auth()->user()->team_id : $participant->team_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'occupation' => $request->occupation,
            'education_level' => $request->education_level,
            'color_blindness' => $request->color_blindness,
            'existing_condition' => $request->existing_condition,
        ]);

        return redirect('/participants')->with('success', 'Participant updated successfully!');
    }

    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);

        if (auth()->user()->role === 'admin' && $participant->team_id !== auth()->user()->team_id) {
            abort(403);
        }
        if (auth()->user()->role === 'researcher' && $participant->user_id !== auth()->id()) {
            abort(403);
        }

        $participant->delete();
        return redirect('/participants')->with('success', 'Participant deleted successfully!');
    }

    public function show($id)
    {
        $participant = \App\Models\Participant::findOrFail($id);
        
        if (auth()->user()->role === 'admin' && $participant->team_id !== auth()->user()->team_id) {
            abort(403);
        }
        if (auth()->user()->role === 'researcher' && $participant->user_id !== auth()->id()) {
            abort(403);
        }
        
        $allTests = \App\Models\TestSession::where('participant_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('participants.show', compact('participant', 'allTests'));
    }
}