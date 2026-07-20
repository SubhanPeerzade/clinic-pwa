@extends('layouts.doctor')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">⏰ Start Time Master</h5>

        @if(session('success'))
            <div class="alert alert-success small">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Start Time -->
        <form method="POST"
              action="{{ route('start.times.store') }}"
              class="row g-2 mb-4">
            @csrf

            <div class="col-md-4 col-12">
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Logic Name (e.g. Morning)"
                       required>
            </div>

            <div class="col-md-4 col-12">
                <input type="text"
                       name="name_mr"
                       class="form-control"
                       placeholder="Marathi Name (e.g. सकाळी)">
            </div>

            <div class="col-md-4 col-12 d-grid">
                <button class="btn btn-primary">
                    ➕ Add Start Time
                </button>
            </div>
        </form>

        <!-- Start Time List -->
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Start Time (En)</th>
                    <th>Start Time (Mr)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($times as $time)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $time->name }}</strong></td>
                    <td>{{ $time->name_mr }}</td>
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
