@extends('layouts.superadmin')

@section('content')
<div class="container">

    <h4 class="fw-bold mb-3">Add Receptionist</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-3 shadow-sm">
        <form method="POST" action="{{ route('admin.reception.store') }}">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button class="btn btn-primary w-100">Create Receptionist</button>
        </form>
    </div>

</div>

<style>
    @media (max-width: 768px) {
        .btn.w-100 {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px !important;
        }
        
        .form-control {
            font-size: 16px;
            padding: 10px 12px;
        }
    }
</style>
@endsection
