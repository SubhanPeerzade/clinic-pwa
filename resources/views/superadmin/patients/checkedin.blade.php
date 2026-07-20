@extends('layouts.superadmin')

@section('content')
<h4 class="mb-3">Checked-in Patients</h4>

@if($patients->isEmpty())
<div class="alert alert-warning">No checked-in patients today.</div>
@else
<div class="table-responsive">
    <table class="table table-bordered m-0">
        <thead>
            <tr>
                <th>Token</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Arrived At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $p)
            <tr>
                <td>#{{ $p->token }}</td>
                <td>{{ $p->patient_name }}</td>
                <td>{{ $p->patient_phone }}</td>
                <td>{{ $p->arrived_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection