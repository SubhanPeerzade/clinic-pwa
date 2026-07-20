<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineCategory;

class MedicineCategoryController extends Controller
{
    public function index()
    {
        $categories = MedicineCategory::orderBy('name')->get();
        return view('masters.medicine_categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:medicine_categories,name',
        ]);

        MedicineCategory::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('medicine.categories.index')
            ->with('success', 'Medicine category added successfully');
    }
}
