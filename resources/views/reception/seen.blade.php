@extends('layouts.app')

@php
    $showBackButton = true;
    $pageTitle = 'Seen Patients Today';
@endphp

@section('content')
<div class="row g-4 px-1 fade-in">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold m-0 text-info">Seen Patients</h3>
                <p class="text-muted small m-0">{{ $seen->count() }} consultations completed today</p>
            </div>
            <a href="{{ route('reception.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-grid-1x2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="col-12 mt-2 fade-in-up">
        @if($seen->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                <i class="bi bi-person-check text-muted opacity-25" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No patients seen yet</h5>
                <p class="small text-muted mb-0">No patient checkups have been marked as completed today</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($seen as $s)
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white hover-scale">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-minor" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ $s->token }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark h6 m-0">{{ $s->patient_name }}</div>
                                    <div class="small text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-telephone"></i> {{ $s->patient_phone }}
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <span class="badge rounded-pill bg-info-subtle text-info px-4 py-2 fw-bold" style="font-size: 0.75rem; border: 1px solid rgba(13, 202, 240, 0.1);">
                                    SEEN
                                </span>
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
