<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineMaster;
use App\Models\MedicineCategory;
use App\Models\DoseMaster;
use App\Models\StartTime;

class MedicineMasterController extends Controller
{
    public function index()
    {
        return view('masters.medicine_masters', [
            'medicines'  => MedicineMaster::with(['category','dose','startTime'])->get(),
            'categories' => MedicineCategory::where('is_active',1)->get(),
            'doses'      => DoseMaster::where('is_active',1)->get(),
            'times'      => StartTime::where('is_active',1)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:medicine_masters,name',
            'medicine_category_id' => 'required',
            'dose_master_id' => 'required',
            'start_time_id' => 'required',
        ]);

        MedicineMaster::create($request->all());

        return redirect()
            ->route('medicine.masters.index')
            ->with('success', 'Medicine added successfully');
    }
}
