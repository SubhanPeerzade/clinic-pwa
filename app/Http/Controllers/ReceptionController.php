<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    /**
     * Show the receptionist dashboard with optional results.
     */
    public function index(Request $request)
    {
        // If a 'q' query param exists we can perform search here (for bookmarkable GET)
        $q = $request->query('q', null);

        if ($q) {
            // reuse the search logic
            return $this->search($request);
        }

        // initial empty view (no patients, no appointments)
        return view('reception.dashboard', [
            'patients' => collect(),
            'appointmentsByPatient' => collect(),
            'query' => null,
        ]);
    }

    /**
     * Search for patients by name, phone, email, or patient_id.
     * - Returns view for normal requests
     * - Returns JSON for AJAX (wantsJson)
     * - Does NOT redirect when no results (shows 'No records found' on the dashboard)
     */
    public function search(Request $request)
{
    $q = trim($request->query('q', ''));

    if ($q === '') {
        if ($request->wantsJson()) {
            return response()->json([
                'count'       => 0,
                'patients'    => [],
                'appointments'=> []
            ], 200);
        }

        return view('reception.dashboard', [
            'patients' => collect(),
            'appointmentsByPatient' => collect(),
            'query' => '',
        ]);
    }

    $patients = Patient::where('first_name', 'like', "%{$q}%")
        ->orWhere('last_name', 'like', "%{$q}%")
        ->orWhere('phone', 'like', "%{$q}%")
        ->orWhere('email', 'like', "%{$q}%")
        ->orWhere('patient_id', 'like', "%{$q}%")
        ->limit(50)
        ->get();

    if ($patients->isEmpty()) {
        if ($request->wantsJson()) {
            return response()->json([
                'count' => 0,
                'patients' => [],
                'appointments' => []
            ], 200);
        }

        return view('reception.dashboard', [
            'patients' => collect(),
            'appointmentsByPatient' => collect(),
            'query' => $q,
        ]);
    }

    // Today
    $tz = config('app.timezone') ?: 'UTC';
    $today = Carbon::now($tz)->toDateString();

    $appointments = Appointment::whereIn('patient_id', $patients->pluck('id'))
        ->whereDate('appointment_date', $today)
        ->get()
        ->keyBy('patient_id'); // 🔥 IMPORTANT

    // AJAX RESPONSE
    if ($request->wantsJson()) {
        return response()->json([
            'count'       => $patients->count(),
            'patients'    => $patients,
            'appointments'=> $appointments->toArray(), // 🔥 FIXED MAPPING
        ], 200);
    }

    return view('reception.dashboard', [
        'patients' => $patients,
        'appointmentsByPatient' => $appointments,
        'query' => $q,
    ]);
}

/**
 * Show full pending list for today.
 */
public function pendingList(Request $request)
{
    $tz = config('app.timezone') ?: 'UTC';
    $today = Carbon::now($tz)->toDateString();

    $pending = Appointment::whereDate('appointment_date', $today)
        ->where('status', 'waiting')
        ->orderBy('token')
        ->get();

    return view('reception.pending', compact('pending'));
}

/**
 * Show full arrived list for today.
 */
public function arrivedList(Request $request)
{
    $tz = config('app.timezone') ?: 'UTC';
    $today = Carbon::now($tz)->toDateString();

    $arrived = Appointment::whereDate('appointment_date', $today)
        ->whereIn('status', ['arrived', 'called', 'in_consultation'])
        ->orderBy('token')
        ->get();

    return view('reception.arrived', compact('arrived'));
}

/**
 * Show full seen list for today.
 */
public function seenList(Request $request)
{
    $tz = config('app.timezone') ?: 'UTC';
    $today = Carbon::now($tz)->toDateString();

    $seen = Appointment::whereDate('appointment_date', $today)
        ->where('status', 'seen')
        ->orderBy('token')
        ->get();

    return view('reception.seen', compact('seen'));
}

}
