@extends('layouts.app')

@php
    $showBackButton = true;
    $pageTitle = 'Daily Report';
@endphp

@section('content')
<div class="row g-4">
    <!-- Header Area -->
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold m-0 text-primary">Daily Patient Report</h3>
                <p class="text-muted m-0">Detailed status breakdown for the selected date</p>
            </div>
            
            <form method="GET" action="{{ route('reports.daily') }}" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar-event"></i></span>
                    <input type="date" name="date" value="{{ $date }}" class="form-control border-start-0 rounded-end-pill px-3" style="min-width: 180px;">
                </div>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm">Filter</button>
            </form>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="col-12">
        <div class="row g-3">
            @php
                $statusColors = [
                    'total' => ['bg' => 'bg-primary-subtle', 'text' => 'text-primary', 'icon' => 'bi-people'],
                    'waiting' => ['bg' => 'bg-light', 'text' => 'text-muted', 'icon' => 'bi-clock'],
                    'arrived' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'icon' => 'bi-check-circle'],
                    'called' => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning', 'icon' => 'bi-megaphone'],
                    'in_consultation' => ['bg' => 'bg-info-subtle', 'text' => 'text-info', 'icon' => 'bi-stethoscope'],
                    'seen' => ['bg' => 'bg-dark-subtle', 'text' => 'text-dark', 'icon' => 'bi-person-check'],
                    'cancelled' => ['bg' => 'bg-danger-subtle', 'text' => 'text-danger', 'icon' => 'bi-x-circle'],
                ];
            @endphp
            @foreach([
                'total'=>'Total',
                'waiting'=>'Waiting',
                'arrived'=>'Arrived',
                'seen'=>'Seen'
            ] as $key => $label)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="{{ $statusColors[$key]['bg'] }} {{ $statusColors[$key]['text'] }} rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi {{ $statusColors[$key]['icon'] }} h4 m-0"></i>
                            </div>
                            <div>
                                <div class="h3 fw-bold m-0">{{ $counts[$key] }}</div>
                                <div class="small text-muted text-uppercase fw-bold">{{ $label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- STATUS GROUPS -->
    <div class="col-12">
        <div class="row g-4">
            @foreach($groups as $status => $items)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold m-0 text-uppercase small text-muted">
                                <i class="bi bi-circle-fill me-2 small {{ $statusColors[$status]['text'] ?? 'text-secondary' }}"></i>
                                {{ str_replace('_',' ', $status) }}
                            </h6>
                            <span class="badge bg-light text-dark rounded-pill border">{{ count($items) }}</span>
                        </div>
                        <div class="card-body p-0">
                            @if(count($items) === 0)
                                <div class="p-4 text-center opacity-50">
                                    <i class="bi bi-inbox text-muted h3"></i>
                                    <p class="small m-0">No records</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($items as $a)
                                        <div class="list-group-item border-0 p-3 hover-bg">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-bold text-dark"><span class="text-primary">#{{ $a->token }}</span> {{ $a->patient_name }}</div>
                                                    <div class="small text-muted"><i class="bi bi-phone me-1"></i> {{ $a->patient_phone }}</div>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted small opacity-25"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .hover-bg:hover { background-color: #f8fafc; }
</style>
@endsection
