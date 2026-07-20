@extends('layouts.superadmin')

@section('content')
<h4 class="mb-3">All Patients</h4>

<div class="table-responsive">
    <table class="table table-hover m-0">
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Registered On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $p)
            <tr>
                <td>{{ $p->patient_id }}</td>
                <td>{{ $p->first_name }} {{ $p->last_name }}</td>
                <td>{{ $p->phone }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $patients->links() }}
@endsection