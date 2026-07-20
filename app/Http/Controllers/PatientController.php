<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function create(Request $request)
    {
       $prefill = session('prefill_search', $request->query('prefill', null));
    return view('patients.create', compact('prefill'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
    'first_name' => 'required|string|max:255',
    'last_name' => 'nullable|string|max:255',
    'date_of_birth' => 'required|date',
    'phone' => 'nullable|string|max:15',
    'email' => 'nullable|email',
    'address' => 'nullable|string',
]);


    // 1️⃣ Get last patient_id
    $lastPatient = Patient::orderBy('id', 'DESC')->first();

    if ($lastPatient && $lastPatient->patient_id) {
        // Extract number part (e.g., PAT-0012 → 12)
        $lastNumber = intval(str_replace('PAT-', '', $lastPatient->patient_id));
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1; // first patient
    }

    // 2️⃣ Format new ID (PAT-0001)
    $data['patient_id'] = 'PAT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    // 3️⃣ Create patient
    $patient = Patient::create($data);

    return redirect()->route('patients.show', $patient->id)
        ->with('success', 'Patient created successfully!');
}

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'patient_id'=>"nullable|string|unique:patients,patient_id,{$patient->id}",
            'first_name'=>'required|string|max:255',
            'last_name'=>'nullable|string|max:255',
            'phone'=>'nullable|string|max:50',
            'email'=>"nullable|email|unique:patients,email,{$patient->id}",
            'address'=>'nullable|string',
        ]);

        $patient->update($data);

        return redirect()->route('patients.show', $patient->id)
            ->with('success', 'Patient updated');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('reception.dashboard')->with('success','Patient deleted');
    }
}
