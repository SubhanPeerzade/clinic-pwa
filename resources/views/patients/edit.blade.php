@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-3">Edit Patient</h4>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('patients.update', $patient->id) }}">
                        @csrf
                        @method('PUT')

                        

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" name="first_name"
                                       required value="{{ $patient->first_name }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control"
                                       name="last_name"
                                       value="{{ $patient->last_name }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control"
                                   name="phone"
                                   value="{{ $patient->phone }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control"
                                   name="email"
                                   value="{{ $patient->email }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3">{{ $patient->address }}</textarea>
                        </div>

                        <button class="btn btn-warning">Update</button>
                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-secondary">Back</a>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
