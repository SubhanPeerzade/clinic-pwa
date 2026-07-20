@extends('layouts.doctor')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0 text-primary">Medicine Master</h3>
    <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Add New Medicine</h5>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('medicine.masters.store') }}" class="row g-3">
            @csrf

            <div class="col-md-3 col-sm-6">
                <label class="form-label small text-muted text-uppercase fw-bold">Medicine Name</label>
                <input name="name" class="form-control rounded-3" placeholder="e.g. Paracetamol" required>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label small text-muted text-uppercase fw-bold">Category</label>
                <select name="medicine_category_id" class="form-select rounded-3" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-6">
                <label class="form-label small text-muted text-uppercase fw-bold">Default Pattern</label>
                <select name="dose_master_id" class="form-select rounded-3" required>
                    <option value="">Select Dose</option>
                    @foreach($doses as $dose)
                        <option value="{{ $dose->id }}">{{ $dose->pattern }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-6">
                <label class="form-label small text-muted text-uppercase fw-bold">Default Start</label>
                <select name="start_time_id" class="form-select rounded-3" required>
                    <option value="">Select Start</option>
                    @foreach($times as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-6 d-flex align-items-end">
                <button class="btn btn-primary w-100 rounded-3 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Add
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 border-0 text-muted small text-uppercase">#</th>
                    <th class="py-3 border-0 text-muted small text-uppercase">Medicine</th>
                    <th class="py-3 border-0 text-muted small text-uppercase">Category</th>
                    <th class="py-3 border-0 text-muted small text-uppercase text-center">Default Pattern</th>
                    <th class="py-3 border-0 text-muted small text-uppercase text-center text-end pe-4">Default Start</th>
                </tr>
            </thead>
            <tbody>
            @foreach($medicines as $m)
                <tr>
                    <td class="ps-4 py-3 text-muted">{{ $loop->iteration }}</td>
                    <td class="py-3 fw-bold">{{ $m->name }}</td>
                    <td class="py-3">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $m->category->name }}</span>
                    </td>
                    <td class="py-3 text-center">
                        <span class="text-dark">{{ $m->dose->pattern }}</span>
                    </td>
                    <td class="py-3 text-center text-end pe-4">
                        <span class="text-muted small"><i class="bi bi-clock me-1"></i> {{ $m->startTime->name }}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .btn.w-100 {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px !important;
        }
        
        .row.g-3 {
            row-gap: 1.5rem;
        }
        
        .col-md-3,
        .col-md-2 {
            min-width: 100%;
        }
        
        .d-flex.align-items-end {
            margin-top: 0.5rem;
        }
    }
</style>

@endsection
