<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionMedicine;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Appointment;


class PrescriptionController extends Controller
{
    public function store(Request $request)
    {
        // ✅ validate incoming JSON
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'medicines'  => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $prescription = Prescription::updateOrCreate(
                ['appointment_id' => $request->appointment_id],
                [
                    'patient_id' => $request->patient_id,
                    'doctor_id' => auth()->id(),
                    'prescription_date' => now(),
                    'diagnosis' => $request->diagnosis,
                    'treatment' => $request->treatment,
                ]
            );

            // Refresh medicines: delete existing and create new ones
            $prescription->medicines()->delete();

            foreach ($request->medicines as $med) {
                PrescriptionMedicine::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name' => $med['name'] ?? '',
                    'category' => $med['category'] ?? '',
                    'dose' => $med['dose'] ?? '',
                    'start_time' => $med['start'] ?? '',
                    'start_time_mr' => $med['start_mr'] ?? '',
                    'days' => $med['days'] ?? 0,
                ]);
            }

            // Update associated appointment status to 'seen'
            $appointment = Appointment::find($request->appointment_id);
            if ($appointment) {
                $appointment->status = 'seen';
                $appointment->save();
            }

            DB::commit();

            return response()->json([
                'print_url' => route('prescription.print', $prescription->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // 🔥 THIS WILL SHOW REAL ERROR
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function print($id)
    {
        $prescription = Prescription::with(['medicines', 'patient'])
            ->findOrFail($id);

        return view('doctor.prescription_print', [
            'patient'      => $prescription->patient,
            'medicines'    => $prescription->medicines,
            'prescription' => $prescription,
        ]);
    }

public function view($appointmentId)
{
    $appointment = Appointment::with([
        'patient',
        'prescription.medicines'
    ])->findOrFail($appointmentId);

    if (!$appointment->prescription) {
        abort(404, 'Prescription not found');
    }

    return view('doctor.prescription-view', [
        'appointment'  => $appointment,
        'patient'      => $appointment->patient,
        'prescription' => $appointment->prescription,
        'medicines'    => $appointment->prescription->medicines,
        'date'         => $appointment->appointment_date
    ]);
}

}
