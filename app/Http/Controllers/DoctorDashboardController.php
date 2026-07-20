<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Hospital;
use App\Models\MedicineCategory;
use App\Models\DoseMaster;
use App\Models\StartTime;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->toDateString();

        $appointments = Appointment::whereDate('appointment_date', $today)
            ->orderBy('token')
            ->get();

        return view('doctor.dashboard', compact('appointments'));
    }

    public function history($patientId)
{
    $patient = Patient::findOrFail($patientId);

    // Load appointments WITH prescription
    $appointments = Appointment::with('prescription')
        ->where('patient_id', $patientId)
        ->orderBy('appointment_date', 'desc')
        ->get();

    return view('doctor.history', [
        'patient' => $patient,
        'appointments' => $appointments
    ]);
}



 public function prescription($appointmentId)
{
    $appointment = Appointment::with(['patient', 'prescription'])
        ->findOrFail($appointmentId);

    $hospital = Hospital::first();
    $categories = MedicineCategory::where('is_active', 1)->get();
    $doses = DoseMaster::all();
    $startTimes = StartTime::all();

    return view('doctor.prescription', compact(
        'appointment',
        'hospital',
        'categories',
        'doses',
        'startTimes'
    ));
}

    public function pendingList()
    {
        $today = Carbon::now()->toDateString();
        $pending = Appointment::whereDate('appointment_date', $today)
            ->where('status', 'waiting')
            ->orderBy('token')
            ->get();

        return view('doctor.pending', compact('pending'));
    }

    public function arrivedList()
    {
        $today = Carbon::now()->toDateString();
        $arrived = Appointment::whereDate('appointment_date', $today)
            ->whereIn('status', ['arrived', 'called', 'in_consultation'])
            ->orderBy('arrived_at', 'desc')
            ->get();

        return view('doctor.arrived', compact('arrived'));
    }

    public function seenList()
    {
        $today = Carbon::now()->toDateString();
        $seen = Appointment::with('prescription')
            ->whereDate('appointment_date', $today)
            ->where('status', 'seen')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('doctor.seen', compact('seen'));
    }

    public function getPrescriptionData(\App\Models\Prescription $prescription)
    {
        return response()->json($prescription->load(['medicines', 'patient']));
    }
}
