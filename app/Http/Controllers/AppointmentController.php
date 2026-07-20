<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Appointment;
use App\Models\AppointmentCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Create an appointment and assign a sequential token for that date (robust approach).
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'patient_id' => 'nullable|exists:patients,id',
        'patient_name' => 'required_without:patient_id|string|max:255',
        'patient_phone' => 'nullable|string|max:50',
        'appointment_date' => 'nullable|date',
        'doctor_id' => 'nullable|integer',
    ]);

    // Use app timezone explicitly so "today" aligns with clinic local date
    $tz = config('app.timezone') ?: 'UTC';
    $date = isset($data['appointment_date'])
        ? \Carbon\Carbon::parse($data['appointment_date'], $tz)->toDateString()
        : \Carbon\Carbon::now($tz)->toDateString();

    $token = null;

    \DB::transaction(function() use (&$token, $date) {
        // lock the counter row for this date (if exists)
        $counter = \App\Models\AppointmentCounter::where('day', $date)->lockForUpdate()->first();

        if (!$counter) {
            // no row for this date — first token of the day
            $counter = \App\Models\AppointmentCounter::create([
                'day' => $date,
                'last_token' => 1,
            ]);
            $token = 1;
        } else {
            // increment safely
            $counter->last_token = $counter->last_token + 1;
            $counter->save();
            $token = $counter->last_token;
        }
    });

    $appointment = \App\Models\Appointment::create([
        'patient_id' => $data['patient_id'] ?? null,
        'patient_name' => $data['patient_name'] ?? null,
        'patient_phone' => $data['patient_phone'] ?? null,
        'appointment_date' => $date,
        'token' => $token,
        'status' => 'waiting',
        'doctor_id' => $data['doctor_id'] ?? null,
    ]);

    if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
    return response()->json(['message' => 'Appointment created', 'appointment' => $appointment], 201);
}

// Redirect receptionist back to dashboard
return redirect()->route('reception.dashboard')
    ->with('success', 'Token #' . $appointment->token . ' created successfully.');

}



    /**
     * Return today's queue for a doctor (or global if doctor_id omitted)
     */
    public function todayQueue(Request $request)
    {
        $date = Carbon::today()->toDateString();
        $doctorId = $request->query('doctor_id', null);

        $query = Appointment::where('appointment_date', $date)
            ->whereIn('status', ['waiting', 'arrived', 'called', 'in_consultation']);

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $queue = $query->orderBy('token')->get();

        return response()->json([
            'date' => $date,
            'count' => $queue->count(),
            'queue' => $queue,
        ]);
    }

    /**
     * Doctor clicks "Call Next" — mark next waiting as 'called' and return it
     */
    public function callNext(Request $request)
    {
        $date = Carbon::today()->toDateString();
        $doctorId = $request->input('doctor_id', null);

        return DB::transaction(function() use ($date, $doctorId) {
            // Find next waiting (lowest token, status waiting)
            $q = Appointment::where('appointment_date', $date)
                ->where('status', 'waiting');

            if ($doctorId) {
                $q->where('doctor_id', $doctorId);
            }

            $next = $q->orderBy('token')->lockForUpdate()->first();

            if (!$next) {
                return response()->json(['message' => 'No waiting patients'], 404);
            }

            // Mark as called
            $next->status = 'called';
            $next->called_at = now();
            $next->save();

            // Optionally, mark previous called -> in_consultation or leave as called
            return response()->json(['message' => 'Patient called', 'appointment' => $next]);
        });
    }

    /**
     * Mark appointment as 'in_consultation' or 'seen'
     */
    public function markSeen(Request $request, Appointment $appointment)
    {
        $action = $request->input('action', 'seen'); // 'in_consultation' or 'seen'
        if (!in_array($action, ['in_consultation','seen','cancelled'])) {
            return response()->json(['message'=>'Invalid action'], 422);
        }

        $appointment->status = $action;
        $appointment->save();

        return response()->json(['message'=>'Status updated','appointment'=>$appointment]);
    }

    public function create(Request $request)
{
    $patientId = $request->query('patient_id');
    $patient = null;
    if ($patientId) {
        $patient = \App\Models\Patient::find($patientId);
    }

    $prefill = [
        'patient_id' => $patient?->id,
        'patient_name' => $patient ? ($patient->first_name . ($patient->last_name ? ' ' . $patient->last_name : '')) : $request->query('patient_name', null),
        'patient_phone' => $patient?->phone ?? $request->query('patient_phone', null),
        'appointment_date' => $request->query('appointment_date', now()->toDateString()),
    ];

    return view('appointments.create', compact('patient', 'prefill'));
}

/**
 * Receptionist checks-in a patient (mark appointment as arrived).
 */
public function checkIn(Request $request, Appointment $appointment)
{
    // Only allow check-in for today's appointments — optional safety:
    $tz = config('app.timezone') ?: 'UTC';
    $today = \Carbon\Carbon::now($tz)->toDateString();
    if ($appointment->appointment_date->toDateString() !== $today) {
        return response()->json(['message' => 'Can only check in today\'s appointments.'], 422);
    }

    // Allow check-in only if currently 'waiting' (adjust if you want more permissive)
    if (! in_array($appointment->status, ['waiting'])) {
        return response()->json([
            'message' => 'Only patients with status "waiting" can be checked in.',
            'appointment' => $appointment
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $appointment->status = 'arrived';
    $appointment->arrived_at = now();
    $appointment->save();

    return response()->json([
        'message' => 'Patient checked in.',
        'appointment' => $appointment,
    ]);
}


public function dailyReport(Request $request)
{
    $tz = config('app.timezone') ?: 'Asia/Kolkata';

    /**
     * ------------------------------------------------
     * REMEMBER FROM WHERE REPORTS WAS OPENED
     * ------------------------------------------------
     * ?from=admin | ?from=reception | ?from=doctor
     */
    if ($request->has('from')) {
        session(['reports_from' => $request->from]);
    }

    $from = session('reports_from', 'admin'); // default fallback

    // ---------------- DATE LOGIC ----------------
    $date = $request->query('date')
        ? \Carbon\Carbon::parse($request->query('date'))->toDateString()
        : \Carbon\Carbon::now($tz)->toDateString();

    // ---------------- FETCH APPOINTMENTS ----------------
    $appointments = \App\Models\Appointment::whereDate('appointment_date', $date)
        ->orderBy('token')
        ->get();

    // ---------------- GROUP BY STATUS ----------------
    $groups = [
        'waiting' => [],
        'arrived' => [],
        'called' => [],
        'in_consultation' => [],
        'seen' => [],
        'cancelled' => [],
    ];

    foreach ($appointments as $a) {
        $status = strtolower($a->status ?? 'waiting');
        if (!isset($groups[$status])) {
            $status = 'waiting';
        }
        $groups[$status][] = $a;
    }

    // ---------------- COUNTS ----------------
    $counts = [
        'total' => count($appointments),
        'waiting' => count($groups['waiting']),
        'arrived' => count($groups['arrived']),
        'called' => count($groups['called']),
        'in_consultation' => count($groups['in_consultation']),
        'seen' => count($groups['seen']),
        'cancelled' => count($groups['cancelled']),
    ];

    // ---------------- BACK ROUTE LOGIC ----------------
    $backRoute = match ($from) {
        'reception' => route('reception.dashboard'),
        'doctor'    => route('doctor.dashboard'),
        default     => route('admin.dashboard'),
    };

    return view('reports.daily-report', compact(
        'date',
        'appointments',
        'groups',
        'counts',
        'backRoute'
    ));
}



    public function toggleNotPresent(Request $request, Appointment $appointment)
    {
        $appointment->is_not_present = ! $appointment->is_not_present;
        $appointment->save();

        return response()->json([
            'message' => 'Status updated.',
            'is_not_present' => $appointment->is_not_present
        ]);
    }

    public function updateRemark(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'remark' => 'nullable|string'
        ]);

        $appointment->remark = $data['remark'];
        $appointment->save();

        return response()->json([
            'message' => 'Remark updated.',
            'remark' => $appointment->remark
        ]);
    }
}
