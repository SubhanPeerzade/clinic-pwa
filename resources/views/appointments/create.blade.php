@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="mb-3">Create Appointment</h5>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('appointments.store') }}">
          @csrf

          {{-- Prefill values when arriving from search --}}
          <input type="hidden" name="patient_id" value="{{ old('patient_id', $prefill['patient_id'] ?? '') }}">

          <div class="mb-3">
            <label class="form-label">Patient Name</label>
            <input type="text" name="patient_name" class="form-control" required
                   value="{{ old('patient_name', $prefill['patient_name'] ?? '') }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="tel" name="patient_phone" class="form-control"
                   value="{{ old('patient_phone', $prefill['patient_phone'] ?? '') }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Appointment Date</label>
            <input type="date" name="appointment_date" class="form-control"
                   value="{{ old('appointment_date', $prefill['appointment_date'] ?? \Carbon\Carbon::today()->toDateString()) }}">
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-primary">Create & Assign Token</button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
