@extends('layouts.doctor')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">💊 Medicine Categories</h5>

        @if(session('success'))
            <div class="alert alert-success small">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Category -->
        <form method="POST" action="{{ route('medicine.categories.store') }}"
              class="row g-2 mb-4">
            @csrf

            <div class="col-md-8 col-12">
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="e.g. Tablet, Syrup, Injection"
                       required>
            </div>

            <div class="col-md-4 col-12 d-grid">
                <button class="btn btn-primary">
                    ➕ Add Category
                </button>
            </div>
        </form>

        <!-- Category List -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name }}</td>
                        <td>
                            <span class="badge {{ $cat->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            No categories added yet
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
