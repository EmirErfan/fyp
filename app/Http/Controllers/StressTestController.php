<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSession;

class StressTestController extends Controller
{
    //stroop
    public function stroop($id)
    {
        $testSession = TestSession::with('participant')->findOrFail($id);
        
        return view('tests.stroop', compact('testSession'));
    }

    //MIST
    public function mist($id)
    {
        $testSession = \App\Models\TestSession::with('participant')->findOrFail($id);
        
        return view('tests.mist', compact('testSession'));
    }

    //TSST
    public function tsst($id)
    {
        $testSession = \App\Models\TestSession::with('participant')->findOrFail($id);
        
        return view('tests.tsst', compact('testSession'));
    }
}