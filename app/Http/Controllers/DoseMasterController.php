<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoseMaster;

class DoseMasterController extends Controller
{
    public function index()
    {
        $doses = DoseMaster::orderBy('pattern')->get();
        return view('masters.dose_masters', compact('doses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pattern' => 'required|string|max:10|unique:dose_masters,pattern',
            'description' => 'nullable|string|max:100',
        ]);

        DoseMaster::create([
            'pattern' => $request->pattern,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('dose.masters.index')
            ->with('success', 'Dose pattern added successfully');
    }
}
