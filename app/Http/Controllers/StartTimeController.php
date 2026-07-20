<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StartTime;

class StartTimeController extends Controller
{
    public function index()
    {
        $times = StartTime::orderBy('name')->get();
        return view('masters.start_times', compact('times'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:start_times,name',
            'name_mr' => 'nullable|string|max:100'
        ]);

        StartTime::create([
            'name' => $request->name,
            'name_mr' => $request->name_mr
        ]);

        return redirect()
            ->route('start.times.index')
            ->with('success', 'Start time added successfully');
    }
}
