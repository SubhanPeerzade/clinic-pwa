@extends('layouts.superadmin')

@section('content')

<!-- ================= DASHBOARD SELECTOR (ADMIN ONLY) ================= -->
@if(auth()->check() && auth()->user()->role === 'doctor')
    <div class="card border-0 shadow-sm mb-4 bg-white overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-primary-subtle text-primary rounded-circle p-2">
                    <i class="bi bi-arrow-left-right h5 m-0"></i>
                </div>
                <h5 class="fw-bold m-0">Switch Dashboard</h5>
            </div>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-stethoscope me-1"></i> Doctor
                </a>
                <a href="{{ route('reception.dashboard') }}" class="btn btn-outline-success rounded-pill px-4">
                    <i class="bi bi-headset me-1"></i> Reception
                </a>
            </div>
        </div>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h4 class="fw-bold m-0 text-primary">System Overview</h4>
        <p class="text-muted small m-0">Global clinic performance and management</p>
    </div>
    <div class="small text-muted bg-white px-3 py-2 rounded-pill shadow-sm border">
        <i class="bi bi-clock me-1"></i> {{ date('D, d M Y') }}
    </div>
</div>

<div class="row g-4 mb-5 fade-in-up">
    <!-- Stats Cards -->
    <div class="col-md-4">
        <a href="{{ route('admin.patients.all') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 p-4 bg-white hover-premium">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="bg-primary-subtle text-primary rounded-4 p-3 shadow-minor">
                        <i class="bi bi-people-fill h3 m-0"></i>
                    </div>
                </div>
                <div class="h1 fw-bold mb-1">{{ $totalPatients }}</div>
                <div class="text-muted small fw-600">Total Registered Patients</div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('admin.patients.today') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 p-4 bg-white hover-premium">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="bg-success-subtle text-success rounded-4 p-3 shadow-minor">
                        <i class="bi bi-calendar-check-fill h3 m-0"></i>
                    </div>
                </div>
                <div class="h1 fw-bold mb-1">{{ $todayPatients }}</div>
                <div class="text-muted small fw-600">Today's Appointments</div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('admin.patients.checkedin') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 p-4 bg-white hover-premium">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="bg-warning-subtle text-warning rounded-4 p-3 shadow-minor">
                        <i class="bi bi-person-check-fill h3 m-0"></i>
                    </div>
                </div>
                <div class="h1 fw-bold mb-1">{{ $checkedIn }}</div>
                <div class="text-muted small fw-600">Currently Checked-in</div>
            </div>
        </a>
    </div>
</div>

<h5 class="fw-bold mb-4">Account Management</h5>

<div class="row g-4">
    <!-- Reception Management -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-light text-primary rounded-circle p-3">
                    <i class="bi bi-person-workspace h4 m-0"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0">Receptionists</h5>
                    <div class="small text-muted">Manage front-desk accounts</div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.reception.index') }}" class="btn btn-primary rounded-pill py-2">
                    View All Receptionists
                </a>
                <a href="{{ route('admin.reception.create') }}" class="btn btn-light text-primary rounded-pill py-2 fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Add New Account
                </a>
            </div>
        </div>
    </div>

    <!-- Doctor Management -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-light text-primary rounded-circle p-3">
                    <i class="bi bi-person-vcard-fill h4 m-0"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0">Doctor Accounts</h5>
                    <div class="small text-muted">Manage clinical staff access</div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-primary rounded-pill py-2">
                    View All Doctors
                </a>
                <a href="{{ route('admin.doctors.create') }}" class="btn btn-light text-primary rounded-pill py-2 fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Add New Account
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-premium {
        transition: var(--transition);
        border: 1px solid transparent !important;
    }
    .hover-premium:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color) !important;
    }
    .shadow-minor {
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .fw-600 { font-weight: 600; }
</style>

@endsection
