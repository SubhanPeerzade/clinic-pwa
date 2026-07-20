@extends('layouts.superadmin')

@section('content')
<h4 class="mb-3">Today's Patients</h4>

@if($patients->isEmpty())
<div class="alert alert-info">No patients for today.</div>
@else
<div class="table-responsive">
    <table class="table table-bordered m-0">
        <thead>
            <tr>
                <th>Token</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $p)
            <tr>
                <td>#{{ $p->token }}</td>
                <td>{{ $p->patient_name }}</td>
                <td>{{ $p->patient_phone }}</td>
                <td>
                    <span class="badge bg-{{ $p->status === 'arrived' ? 'success' : 'secondary' }}">
                        {{ ucfirst($p->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection