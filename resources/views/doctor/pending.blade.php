@extends('layouts.doctor')

@php
    $pageTitle = 'Pending Patients Today';
@endphp

@section('content')
<div class="row g-4 px-1 fade-in">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold m-0 text-primary">Pending Patients</h3>
                <p class="text-muted small m-0">{{ $pending->count() }} patients waiting in the queue</p>
            </div>
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-grid-1x2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="col-12 mt-2 fade-in-up">
        @if($pending->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                <i class="bi bi-clock-history text-muted opacity-25" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No pending patients</h5>
                <p class="small text-muted mb-0">Today's pending list is currently empty</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($pending as $appt)
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white hover-scale">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-minor" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ $appt->token }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark h6 m-0">{{ $appt->patient_name }}</div>
                                    <div class="small text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-telephone"></i> {{ $appt->patient_phone }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 ms-auto">
                                <a href="{{ route('doctor.history', $appt->patient_id) }}" class="btn btn-outline-secondary rounded-pill px-3">
                                    <i class="bi bi-clock-history me-1"></i> History
                                </a>
                                <a href="{{ route('doctor.prescription', $appt->id) }}" class="btn btn-premium rounded-pill px-4">
                                    <i class="bi bi-prescription2 me-1"></i> Add Rx
                                </a>
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
