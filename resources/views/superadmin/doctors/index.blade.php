@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">

    <h4 class="fw-bold mb-3">Doctors</h4>

    <a href="{{ route('admin.doctors.create') }}" class="btn btn-success mb-3">
        + Add Doctor
    </a>

    <!-- ================= MOBILE VIEW (CARDS) ================= -->
    <div class="d-md-none">
        @foreach($doctors as $d)
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">
                    <div class="fw-bold fs-6 mb-1">
                        {{ $d->name }}
                    </div>

                    <div class="text-muted small mb-2">
                        {{ $d->email }}
                    </div>

                    <span class="badge bg-info">
                        Doctor
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ================= DESKTOP VIEW (TABLE) ================= -->
    <div class="card shadow-sm border-0 d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width:120px">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($doctors as $d)
                    <tr>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->email }}</td>
                        <td>
                            <span class="badge bg-info">Doctor</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
