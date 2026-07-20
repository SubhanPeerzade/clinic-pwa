@extends('layouts.doctor')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">⏱ Dose Master</h5>

        @if(session('success'))
            <div class="alert alert-success small">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Dose -->
        <form method="POST"
              action="{{ route('dose.masters.store') }}"
              class="row g-2 mb-4">
            @csrf

            <div class="col-md-4 col-12">
                <input type="text"
                       name="pattern"
                       class="form-control"
                       placeholder="e.g. 1-0-1"
                       required>
            </div>

            <div class="col-md-5 col-12">
                <input type="text"
                       name="description"
                       class="form-control"
                       placeholder="Morning & Night">
            </div>

            <div class="col-md-3 col-12 d-grid">
                <button class="btn btn-primary">
                    ➕ Add Dose
                </button>
            </div>
        </form>

        <!-- Dose List -->
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Dose Pattern</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($doses as $dose)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $dose->pattern }}</strong></td>
                    <td>{{ $dose->description }}</td>
                    <td>
                        <span class="badge bg-success">Active</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
