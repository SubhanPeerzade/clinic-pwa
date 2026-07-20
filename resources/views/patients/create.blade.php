@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-primary text-white p-4 border-0">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('reception.dashboard') }}" class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border: none;">
                        <i class="bi bi-arrow-left text-white"></i>
                    </a>
                    <div>
                        <h4 class="fw-bold m-0">New Patient Registration</h4>
                        <p class="small opacity-75 mb-0">Complete the form to create a new record</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-3">
                        <div class="fw-bold mb-2"><i class="bi bi-exclamation-circle-fill me-2"></i> Issues detected:</div>
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6 text-field-container">
                            <label class="form-label small text-muted text-uppercase fw-bold ls-1 mb-2">First Name *</label>
                            <div class="input-group-custom shadow-sm rounded-3 overflow-hidden">
                                <span class="input-icon"><i class="bi bi-person text-primary"></i></span>
                                <input type="text" name="first_name" class="form-control-custom" required
                                       value="{{ old('first_name', $prefill ?? '') }}" placeholder="Rajesh" autocomplete="given-name" />
                            </div>
                        </div>

                        <div class="col-md-6 text-field-container">
                            <label class="form-label small text-muted text-uppercase fw-bold ls-1 mb-2">Last Name</label>
                            <div class="input-group-custom shadow-sm rounded-3 overflow-hidden">
                                <span class="input-icon"><i class="bi bi-person text-primary opacity-50"></i></span>
                                <input type="text" name="last_name" class="form-control-custom" 
                                       placeholder="Khanna" autocomplete="family-name" />
                            </div>
                        </div>

                        <div class="col-md-6 text-field-container">
                            <label class="form-label small text-muted text-uppercase fw-bold ls-1 mb-2">Date of Birth *</label>
                            <div class="input-group-custom shadow-sm rounded-3 overflow-hidden">
                                <span class="input-icon"><i class="bi bi-calendar-event text-primary"></i></span>
                                <input type="date" name="date_of_birth" class="form-control-custom" 
                                       value="{{ old('date_of_birth') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 text-field-container">
                            <label class="form-label small text-muted text-uppercase fw-bold ls-1 mb-2">Phone Number</label>
                            <div class="input-group-custom shadow-sm rounded-3 overflow-hidden">
                                <span class="input-icon"><i class="bi bi-telephone text-primary"></i></span>
                                <input type="tel" name="phone" class="form-control-custom" inputmode="tel" 
                                       placeholder="9876543210" autocomplete="tel" />
                            </div>
                        </div>

                        <div class="col-12 text-field-container">
                            <label class="form-label small text-muted text-uppercase fw-bold ls-1 mb-2">Email Address</label>
                            <div class="input-group-custom shadow-sm rounded-3 overflow-hidden">
                                <span class="input-icon"><i class="bi bi-envelope text-primary"></i></span>
                                <input type="email" name="email" class="form-control-custom" 
                                       placeholder="rajesh@example.com" autocomplete="email" />
                            </div>
                        </div>

                        <div class="col-12 text-field-container">
                            <label class="form-label small text-muted text-uppercase fw-bold ls-1 mb-2">Full Address</label>
                            <div class="input-group-custom shadow-sm rounded-3 overflow-hidden align-items-start">
                                <span class="input-icon pt-3"><i class="bi bi-geo-alt text-primary opacity-75"></i></span>
                                <textarea name="address" class="form-control-custom py-3" rows="3" 
                                          placeholder="Enter residential address..." autocomplete="street-address"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 py-3 shadow-lg fw-bold mb-3">
                            <i class="bi bi-check-circle-fill me-2"></i> Register & Create Profile
                        </button>
                        <a href="{{ route('reception.dashboard') }}" class="btn btn-light rounded-pill w-100 py-3 text-muted border fw-semibold">
                            Return to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .input-group-custom {
        display: flex;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    .input-group-custom:focus-within {
        background: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
    }
    .input-icon {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        background: transparent;
    }
    .form-control-custom {
        border: none;
        background: transparent;
        flex: 1;
        padding: 14px 16px 14px 0;
        font-size: 1rem;
        color: var(--text-dark);
        outline: none;
    }
    .form-control-custom::placeholder { color: #94a3b8; opacity: 0.8; }
    
    @media (max-width: 768px) {
        .card-body { padding: 1rem !important; }
        .form-control-custom { font-size: 1rem; }
        
        .btn.w-100, button[type="submit"] {
            width: 100% !important;
            max-width: 100%;
            box-sizing: border-box;
            padding: 8px 10px !important;
            font-size: 0.85rem;
        }
        
        button[type="submit"].btn {
            padding: 8px 10px !important;
        }
        
        a.btn {
            padding: 8px 10px !important;
        }
        
        .input-icon {
            padding: 10px 12px;
        }
        
        .form-control-custom {
            padding: 10px 12px 10px 0;
        }
    }
</style>
@endsection
