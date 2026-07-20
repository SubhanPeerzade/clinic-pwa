@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h4 class="fw-bold m-0 text-primary">Patient Profile</h4>
                <div class="badge bg-primary-subtle text-primary rounded-pill px-3">ID: {{ $patient->patient_id ?? '-' }}</div>
            </div>
            
            <div class="card-body p-4 p-md-5">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="text-center mb-5">
                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; font-size: 3rem; font-weight: 700;">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                    </div>
                    <h2 class="fw-bold m-0">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
                    <p class="text-muted">Registered Patient</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-4 bg-light">
                            <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Phone Number</label>
                            <div class="fw-bold h5 mb-0"><i class="bi bi-telephone text-primary me-2"></i> {{ $patient->phone ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4 bg-light">
                            <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Email Address</label>
                            <div class="fw-bold h5 mb-0"><i class="bi bi-envelope text-primary me-2"></i> {{ $patient->email ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4 bg-light">
                            <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Age</label>
                            <div class="fw-bold h5 mb-0"><i class="bi bi-calendar-event text-primary me-2"></i> {{ $patient->age }} Years</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4 bg-light">
                            <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Full Address</label>
                            <div class="fw-bold h6 mb-0 text-muted"><i class="bi bi-geo-alt text-primary me-2"></i> {{ $patient->address ?? 'No address provided' }}</div>
                        </div>
                    </div>
                </div>

                <hr class="my-5 opacity-25">

                <div class="d-flex flex-column flex-md-row gap-3">
                    <a href="{{ route('appointments.create',['patient_id'=>$patient->id]) }}" class="btn btn-primary btn-lg rounded-pill px-5 flex-grow-1 shadow">
                        <i class="bi bi-calendar-plus me-2"></i>Book Appointment
                    </a>
                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-lg rounded-pill px-4 text-white">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('patients.destroy', $patient->id) }}" class="d-inline-block flex-grow-1 flex-md-grow-0">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-lg rounded-pill px-4 w-100"
                                onclick="return confirm('Search says: Are you sure you want to delete this patient record permanentely?')">
                            <i class="bi bi-trash3 me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-footer bg-light p-3 text-center">
                <a href="{{ route('reception.dashboard') }}" class="text-decoration-none text-muted small fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .btn.btn-lg,
        .btn-lg.rounded-pill {
            padding: 8px 12px !important;
            font-size: 0.85rem;
            min-height: 40px;
        }
        
        .btn.w-100 {
            width: 100% !important;
            box-sizing: border-box;
        }
        
        .d-flex.flex-column.flex-md-row.gap-3 {
            gap: 0.5rem !important;
        }
        
        .flex-grow-1 {
            flex-grow: 1;
        }
        
        .px-4, .px-5 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
    }
</style>
@endsection
