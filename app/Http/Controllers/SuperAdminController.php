<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use App\Models\Appointment;

class SuperAdminController extends Controller
{
    // SUPER ADMIN MAIN DASHBOARD
public function dashboard()
{
    // Total number of patients
    $totalPatients = \App\Models\Patient::count();

    // Today's appointments
    $todayPatients = \App\Models\Appointment::whereDate('appointment_date', today())->count();

    // Checked-in patients for today
    $checkedIn = \App\Models\Appointment::whereDate('appointment_date', today())
                    ->where('status', 'arrived')
                    ->count();

    return view('superadmin.dashboard', compact(
        'totalPatients',
        'todayPatients',
        'checkedIn'
    ));
}



    // Receptionists
    public function receptionIndex()
    {
        $receptionists = User::where('role', 'receptionist')->get();
        return view('superadmin.reception.index', compact('receptionists'));
    }

    public function receptionCreate()
    {
        return view('superadmin.reception.create');
    }

    public function receptionStore(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'receptionist',  // correct
    ]);

    return redirect()->route('admin.reception.index')->with('success', 'Receptionist added successfully');
}


    // Doctors
    public function doctorIndex()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('superadmin.doctors.index', compact('doctors'));
    }

    public function doctorCreate()
    {
        return view('superadmin.doctors.create');
    }

    public function doctorStore(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'doctor',  // ✅ FIXED
    ]);

    return redirect()->route('admin.doctors.index')->with('success', 'Doctor added successfully');
}

    public function allPatients()
{
    $patients = Patient::latest()->paginate(20);
    return view('superadmin.patients.all', compact('patients'));
}

public function todayPatients()
{
    $patients = Appointment::whereDate('appointment_date', today())
                ->orderBy('token')
                ->get();
    return view('superadmin.patients.today', compact('patients'));
}

public function checkedInPatients()
{
    $patients = Appointment::whereDate('appointment_date', today())
                ->where('status', 'arrived')
                ->orderBy('token')
                ->get();
    return view('superadmin.patients.checkedin', compact('patients'));
}

}
