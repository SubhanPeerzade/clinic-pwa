@extends('layouts.doctor')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h3 class="fw-bold m-0 text-primary">Patient Queue</h3>
        <p class="text-muted small m-0">Manage your consultations for today</p>
    </div>
    <span class="badge bg-white text-primary border border-primary-subtle px-3 py-2 rounded-pill shadow-sm animate-pulse">
        <i class="bi bi-calendar-event me-1"></i> {{ date('d M, Y') }}
    </span>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4 fade-in-up">
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm p-4 bg-white text-center hover-scale">
            <div class="text-primary mb-2">
                <i class="bi bi-people-fill h3"></i>
            </div>
            <div class="small text-muted mb-1 fw-600">Total</div>
            <div class="h3 fw-bold m-0">{{ $appointments->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm p-4 bg-white text-center hover-scale">
            <div class="text-warning mb-2">
                <i class="bi bi-hourglass-split h3"></i>
            </div>
            <div class="small text-muted mb-1 fw-600">Waiting</div>
            <div class="h3 fw-bold m-0 text-warning">{{ $appointments->where('status', 'waiting')->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm p-4 bg-white text-center hover-scale">
            <div class="text-success mb-2">
                <i class="bi bi-person-check h3"></i>
            </div>
            <div class="small text-muted mb-1 fw-600">Arrived</div>
            <div class="h3 fw-bold m-0 text-success">{{ $appointments->whereIn('status', ['arrived', 'called', 'in_consultation'])->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm p-4 bg-white text-center hover-scale">
            <div class="text-info mb-2">
                <i class="bi bi-check2-all h3"></i>
            </div>
            <div class="small text-muted mb-1 fw-600">Seen</div>
            <div class="h3 fw-bold m-0 text-info">{{ $appointments->where('status', 'seen')->count() }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- PENDING QUEUE -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100 bg-white rounded-4 overflow-hidden">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-3">
                <h5 class="fw-bold m-0 text-warning">Pending Queue</h5>
                <a href="{{ route('doctor.pending') }}" class="small text-primary text-decoration-none fw-bold">View All</a>
            </div>
            <div class="card-body p-0">
                @php
                    $pending = $appointments->where('status', 'waiting')->take(5);
                @endphp
                @if($pending->isEmpty())
                    <div class="text-center py-4 opacity-50 small">No pending patients</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($pending as $appt)
                            @include('doctor.partials.queue-item', ['appt' => $appt, 'type' => 'pending'])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ARRIVED PATIENTS -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100 bg-white rounded-4 overflow-hidden">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-3">
                <h5 class="fw-bold m-0 text-success">Arrived Patients</h5>
                <a href="{{ route('doctor.arrived') }}" class="small text-primary text-decoration-none fw-bold">View All</a>
            </div>
            <div class="card-body p-0">
                @php
                    $arrived = $appointments->whereIn('status', ['arrived', 'called', 'in_consultation'])
                                 ->sortByDesc('arrived_at')
                                 ->take(5);
                @endphp
                @if($arrived->isEmpty())
                    <div class="text-center py-4 opacity-50 small">No arrived patients</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($arrived as $appt)
                            @include('doctor.partials.queue-item', ['appt' => $appt, 'type' => 'arrived'])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale {
        transition: var(--transition);
    }
    .hover-scale:hover {
        transform: translateY(-5px) scale(1.02);
    }
    .queue-item-card {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    .queue-item-card:hover { 
        background-color: #f8fafc; 
    }
    .token-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.8rem;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .animate-pulse {
        animation: pulse 2s infinite ease-in-out;
    }
</style>

@endsection
