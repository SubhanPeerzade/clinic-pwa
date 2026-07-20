<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Auth\DoctorLoginController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\MedicineCategoryController;
use App\Http\Controllers\DoseMasterController;
use App\Http\Controllers\StartTimeController;
use App\Http\Controllers\MedicineMasterController;
use App\Http\Controllers\MedicineSearchController;
use App\Http\Controllers\PrescriptionController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    // receptionist dashboard (search)
    Route::get('/reception', [ReceptionController::class, 'index'])->name('reception.dashboard');

    // search endpoint (GET so it's bookmarkable)
    Route::get('/reception/search', [ReceptionController::class, 'search'])->name('reception.search');

    // full lists
    Route::get('/reception/pending', [ReceptionController::class, 'pendingList'])->name('reception.pending');
    Route::get('/reception/arrived', [ReceptionController::class, 'arrivedList'])->name('reception.arrived');
    Route::get('/reception/seen', [ReceptionController::class, 'seenList'])->name('reception.seen');

    // patient resource (exclude index if you prefer)
    Route::resource('patients', PatientController::class)->except(['index']);
});

Route::get('login', [LoginController::class, 'show'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'show'])->name('register');
Route::post('register', [RegisterController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class,'store'])->name('appointments.store');
    Route::get('/appointments/today', [AppointmentController::class,'todayQueue'])->name('appointments.today');
    Route::post('/appointments/call-next', [AppointmentController::class,'callNext'])->name('appointments.callNext');
    Route::post('/appointments/{appointment}/status', [AppointmentController::class,'markSeen'])->name('appointments.status');
    // receptionist check-in
    Route::post('/appointments/{appointment}/checkin', [AppointmentController::class, 'checkIn'])->name('appointments.checkin');
    Route::post('/appointments/{appointment}/toggle-not-present', [AppointmentController::class, 'toggleNotPresent'])->name('appointments.toggleNotPresent');
    Route::post('/appointments/{appointment}/remark', [AppointmentController::class, 'updateRemark'])->name('appointments.updateRemark');
    Route::get('/reports/daily', [AppointmentController::class, 'dailyReport'])->name('reports.daily');

});

Route::middleware(['auth', 'is_admin'])->group(function () {

    Route::get('/admin/dashboard', [SuperAdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Receptionists
    Route::get('/admin/receptionists', [SuperAdminController::class, 'receptionIndex'])
        ->name('admin.reception.index');

    Route::get('/admin/receptionists/create', [SuperAdminController::class, 'receptionCreate'])
        ->name('admin.reception.create');

    Route::post('/admin/receptionists/store', [SuperAdminController::class, 'receptionStore'])
        ->name('admin.reception.store');

    // Doctors
    Route::get('/admin/doctors', [SuperAdminController::class, 'doctorIndex'])
        ->name('admin.doctors.index');

    Route::get('/admin/doctors/create', [SuperAdminController::class, 'doctorCreate'])
        ->name('admin.doctors.create');

    Route::post('/admin/doctors/store', [SuperAdminController::class, 'doctorStore'])
        ->name('admin.doctors.store');

    Route::get('/admin/patients', [SuperAdminController::class, 'allPatients'])
    ->name('admin.patients.all');

    Route::get('/admin/patients/today', [SuperAdminController::class, 'todayPatients'])
    ->name('admin.patients.today');

    Route::get('/admin/patients/checkedin', [SuperAdminController::class, 'checkedInPatients'])
    ->name('admin.patients.checkedin');

});

// Doctor routes
// DOCTOR LOGIN
Route::get('/doctor/login', [DoctorLoginController::class, 'show'])->name('doctor.login');
Route::post('/doctor/login', [DoctorLoginController::class, 'login'])->name('doctor.login.submit');
Route::post('/doctor/logout', [DoctorLoginController::class, 'logout'])->name('doctor.logout');

// DOCTOR PROTECTED ROUTES
// DOCTOR PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {

    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
        ->name('doctor.dashboard');

    Route::get('/doctor/pending', [DoctorDashboardController::class, 'pendingList'])->name('doctor.pending');
    Route::get('/doctor/arrived', [DoctorDashboardController::class, 'arrivedList'])->name('doctor.arrived');
    Route::get('/doctor/seen', [DoctorDashboardController::class, 'seenList'])->name('doctor.seen');

    Route::get('/doctor/history/{patient}', [DoctorDashboardController::class, 'history'])
        ->name('doctor.history');

    // ✅ FIX: use appointment as parameter
    Route::get(
        '/doctor/prescription/create/{appointment}',
        [DoctorDashboardController::class, 'prescription']
    )->name('doctor.prescription');

    Route::get(
        '/doctor/prescription/edit/{appointment}',
        [DoctorDashboardController::class, 'prescription']
    )->name('doctor.prescription.edit');

    Route::get(
        '/doctor/prescription/data/{prescription}',
        [DoctorDashboardController::class, 'getPrescriptionData']
    )->name('doctor.prescription.data');

});



Route::middleware(['auth'])->group(function () {

    Route::get('/medicine-categories', 
        [MedicineCategoryController::class, 'index']
    )->name('medicine.categories.index');

    Route::post('/medicine-categories', 
        [MedicineCategoryController::class, 'store']
    )->name('medicine.categories.store');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/masters/dose-masters',
        [DoseMasterController::class, 'index']
    )->name('dose.masters.index');

    Route::post('/masters/dose-masters',
        [DoseMasterController::class, 'store']
    )->name('dose.masters.store');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/masters/start-times',
        [StartTimeController::class, 'index']
    )->name('start.times.index');

    Route::post('/masters/start-times',
        [StartTimeController::class, 'store']
    )->name('start.times.store');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/masters/medicine-masters',
        [MedicineMasterController::class, 'index']
    )->name('medicine.masters.index');

    Route::post('/masters/medicine-masters',
        [MedicineMasterController::class, 'store']
    )->name('medicine.masters.store');

});

Route::middleware(['auth'])->get(
    '/ajax/medicines',
    [MedicineSearchController::class, 'search']
)->name('ajax.medicines.search');

Route::post('/doctor/prescription/save',
    [PrescriptionController::class, 'store']
)->name('prescription.store');

Route::get('/doctor/prescription/{id}/print',
    [PrescriptionController::class, 'print']
)->name('prescription.print');

Route::get(
    '/doctor/prescription/view/{appointment}',
    [PrescriptionController::class, 'view']
)->name('prescription.view');
