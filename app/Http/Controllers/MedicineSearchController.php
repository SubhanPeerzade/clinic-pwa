<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineMaster;

class MedicineSearchController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->get('q');

        $medicines = MedicineMaster::with(['category', 'dose', 'startTime'])
            ->where('name', 'like', "%{$term}%")
            ->where('is_active', 1)
            ->limit(10)
            ->get();

        return response()->json($medicines);
    }
}
