@extends('layouts.doctor')

@php
    $pageTitle = 'Arrived Patients Today';
@endphp

@section('content')
<div class="row g-4 px-1 fade-in">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold m-0 text-success">Arrived Patients</h3>
                <p class="text-muted small m-0">{{ $arrived->count() }} patients checked-in today</p>
            </div>
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-grid-1x2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="col-12 mt-2 fade-in-up">
        @if($arrived->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                <i class="bi bi-check-circle text-muted opacity-25" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No arrived patients</h5>
                <p class="small text-muted mb-0">No patients have checked-in yet today</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($arrived as $appt)
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white hover-scale">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-minor" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ $appt->token }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark h6 m-0">{{ $appt->patient_name }}</div>
                                    <div class="small text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-telephone"></i> {{ $appt->patient_phone }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 ms-auto align-items-center">
                                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 fw-bold me-2" style="font-size: 0.75rem; border: 1px solid rgba(25, 135, 84, 0.1);">
                                    {{ strtoupper($appt->status) }}
                                </span>
                                <a href="{{ route('doctor.history', $appt->patient_id) }}" class="btn btn-outline-secondary rounded-pill px-3">
                                    <i class="bi bi-clock-history me-1"></i> History
                                </a>
                                @if($appt->status !== 'seen')
                                    <a href="{{ route('doctor.prescription', $appt->id) }}" class="btn btn-premium rounded-pill px-4">
                                        <i class="bi bi-prescription2 me-1"></i> Add Rx
                                    </a>
                                @else
                                    <a href="{{ route('prescription.view', $appt->id) }}" class="btn btn-outline-success rounded-pill px-4">
                                        <i class="bi bi-eye me-1"></i> View Rx
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .space-y-3 > * + * { margin-top: 1rem; }
    .hover-scale { transition: var(--transition); }
    .hover-scale:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .shadow-minor { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>
@endsection
