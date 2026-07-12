<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeamManagementController extends Controller
{
    public function index()
    {
        $team = auth()->user()->team;
        $researchers = User::where('team_id', auth()->user()->team_id)
                           ->where('role', 'researcher')
                           ->get();

        return view('team-management.index', compact('team', 'researchers'));
    }



    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->team_id !== auth()->user()->team_id) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Researcher removed successfully.');
    }
}
