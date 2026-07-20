@extends('layouts.doctor')

@section('content')

<nav aria-label="breadcrumb" class="mb-0 fade-in d-none d-md-block">
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}" class="text-decoration-none text-primary fw-600">Dashboard</a></li>
        <li class="breadcrumb-item active fw-600" aria-current="page">Patient History</li>
    </ol>
</nav>

<div class="row g-3 fade-in-up">
    <!-- ================= PATIENT INFO ================= -->
    <div class="col-lg-4">
        <div class="card border border-light shadow-sm p-4 h-100 bg-white sticky-top shadow-hover-md" style="top: 100px; z-index: 10; border-radius: 20px;">
            <div class="text-center mb-3">
                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-minor" style="width: 110px; height: 110px; font-size: 2.5rem; font-weight: 800;">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                </div>
                <h4 class="fw-bold m-0 text-dark" style="font-size: 1.25rem;">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                <div class="badge bg-light text-muted border px-3 py-1 mt-2">ID: {{ $patient->patient_id }}</div>
            </div>

            <hr class="opacity-10 my-2">

            <div class="space-y-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="bg-light rounded-3 p-2 text-primary">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Phone</div>
                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ $patient->phone }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="bg-light rounded-3 p-2 text-primary">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ $patient->email }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light rounded-3 p-2 text-primary">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Age</div>
                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ $patient->age }} Years</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= VISIT LOGS ================= -->
    <div class="col-lg-8">
        <h5 class="fw-bold mb-2 d-flex align-items-center gap-2" style="font-size: 1rem;">
            <i class="bi bi-clock-history text-primary"></i> Visit Timeline
        </h5>

        @if($appointments->isEmpty())
            <div class="card border-0 shadow-sm p-3 text-center bg-white">
                <i class="bi bi-calendar-x text-muted display-4 mb-3"></i>
                <p class="text-muted m-0">No previous visits recorded for this patient.</p>
            </div>
        @else

        <!-- ================= MOBILE VISIT LIST ================= -->
        <div class="d-md-none space-y-2">
            @foreach($appointments as $appt)
                <div class="card border-0 shadow-sm mb-2 bg-white overflow-hidden hover-premium-shadow rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary h6 m-0" style="font-size: 0.95rem;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M, Y') }}</span>
                        @if($appt->status === 'seen')
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem;">SEEN</span>
                        @else
                            <span class="badge bg-light text-muted rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem;">{{ strtoupper($appt->status) }}</span>
                        @endif
                    </div>
                    <div class="card-body p-3">
                        <div class="small text-muted mb-2 d-flex align-items-center gap-1">
                            <i class="bi bi-hash"></i> Token: <span class="fw-bold text-dark">#{{ $appt->token }}</span>
                        </div>
                        
                        @if($appt->prescription)
                            <div class="mb-2">
                                <label class="fw-bold small text-muted text-uppercase mb-1 d-block" style="letter-spacing: 0.5px;">Diagnosis</label>
                                <div class="bg-light p-2 rounded-4 small border-start border-primary border-4">
                                    {{ $appt->prescription->diagnosis ?: 'No diagnosis recorded' }}
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fw-bold small text-muted text-uppercase mb-1 d-block" style="letter-spacing: 0.5px;">Treatment</label>
                                <div class="bg-light p-2 rounded-4 small border-start border-info border-4">
                                    {{ $appt->prescription->treatment ?: 'No treatment advice recorded' }}
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary rounded-pill py-3 px-3 flex-fill fw-600 view-rx-btn" data-rx-id="{{ $appt->prescription->id }}">
                                    <i class="bi bi-eye me-1"></i> View Rx
                                </button>
                                <a href="{{ route('doctor.prescription.edit', $appt->id) }}" class="btn btn-premium rounded-pill py-3 px-3 flex-fill fw-600">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                            </div>
                        @else
                            <div class="text-muted small italic p-4 bg-light rounded-4 text-center border-dashed">
                                <i class="bi bi-journal-x d-block h3 opacity-25"></i>
                                No clinical notes recorded for this visit.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ================= DESKTOP VISIT TABLE ================= -->
        <div class="card border-0 shadow-sm d-none d-md-block bg-white overflow-hidden rounded-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0" style="table-layout: fixed;">
                    <thead>
                        <tr class="bg-light">
                            <th style="width: 140px;" class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">Visit Date</th>
                            <th style="width: 90px;" class="py-3 border-0 text-muted small text-uppercase text-center fw-bold">Token</th>
                            <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Diagnosis</th>
                            <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Treatment</th>
                            <th style="width: 180px;" class="pe-4 py-3 border-0 text-muted small text-uppercase text-center fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($appointments as $appt)
                            <tr class="hover-bg-light transition-base">
                                <td class="ps-4 py-4">
                                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M, Y') }}</div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('h:i A') }}</div>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-3 py-2 fw-bold">#{{ $appt->token }}</span>
                                </td>
                                <td class="py-4">
                                    @if($appt->prescription)
                                        <div class="small text-truncate-2 text-dark" title="{{ $appt->prescription->diagnosis }}">
                                            {{ $appt->prescription->diagnosis ?: '—' }}
                                        </div>
                                    @else
                                        <span class="text-muted small italic">—</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    @if($appt->prescription)
                                        <div class="small text-truncate-2 text-dark" title="{{ $appt->prescription->treatment }}">
                                            {{ $appt->prescription->treatment ?: '—' }}
                                        </div>
                                    @else
                                        <span class="text-muted small italic">—</span>
                                    @endif
                                </td>
                                <td class="pe-4 py-4 text-center">
                                    @if($appt->prescription)
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill w-40px h-40px d-flex align-items-center justify-content-center view-rx-btn shadow-sm" data-rx-id="{{ $appt->prescription->id }}" title="View Prescription">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <a href="{{ route('doctor.prescription.edit', $appt->id) }}" class="btn btn-sm btn-outline-info rounded-pill w-40px h-40px d-flex align-items-center justify-content-center shadow-sm" title="Edit Prescription">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="{{ route('prescription.print', $appt->prescription->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill w-40px h-40px d-flex align-items-center justify-content-center shadow-sm" title="Print Prescription">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .sticky-lg-top { transition: top 0.3s ease; }
    .space-y-3 > * + * { margin-top: 1rem; }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .w-40px { width: 40px; }
    .h-40px { height: 40px; }
    .transition-base { transition: var(--transition); }
    .hover-bg-light:hover { background-color: #f8fafc !important; }
    .hover-premium-shadow { transition: var(--transition); }
    .hover-premium-shadow:hover { box-shadow: var(--shadow-md) !important; transform: translateY(-2px); }
    .border-dashed { border: 2px dashed #e2e8f0; }
</style>

@push('modals')
<!-- ================= VIEW RX MODAL ================= -->
<div class="modal fade" id="viewRxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-prescription2 me-2"></i>Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="viewRxModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <a href="#" id="modalPrintBtn" target="_blank" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-printer me-1"></i> Print
                </a>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rxModal = new bootstrap.Modal(document.getElementById('viewRxModal'));
    const modalBody = document.getElementById('viewRxModalBody');
    const modalPrintBtn = document.getElementById('modalPrintBtn');

    document.querySelectorAll('.view-rx-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const rxId = this.dataset.rxId;
            modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
            modalPrintBtn.href = `/doctor/prescription/${rxId}/print`;
            rxModal.show();

            fetch(`/doctor/prescription/data/${rxId}`)
                .then(res => res.json())
                .then(data => {
                    let medicinesHtml = '';
                    data.medicines.forEach((m, index) => {
                        medicinesHtml += `
                            <tr>
                                <td class="small text-muted">${index + 1}</td>
                                <td>
                                    <div class="fw-bold small">${m.medicine_name}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">${m.category}</div>
                                </td>
                                <td class="small">${m.dose}</td>
                                <td class="small">${m.start_time_mr || m.start_time}</td>
                                <td class="small fw-bold">${m.days}</td>
                            </tr>
                        `;
                    });

                    modalBody.innerHTML = `
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Diagnosis</label>
                                <div class="p-3 bg-light rounded-3 small">${data.diagnosis || '—'}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Treatment / Advice</label>
                                <div class="p-3 bg-light rounded-3 small">${data.treatment || '—'}</div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Medicines</label>
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr class="text-muted small border-bottom">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Medicine</th>
                                        <th class="border-0">Dosage</th>
                                        <th class="border-0">Timing</th>
                                        <th class="border-0">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>${medicinesHtml}</tbody>
                            </table>
                        </div>
                    `;
                })
                .catch(err => {
                    modalBody.innerHTML = '<div class="alert alert-danger">Failed to load prescription data.</div>';
                });
        });
    });
});
</script>

<style>
    @media (max-width: 768px) {
        .card.sticky-top {
            top: 56px !important; /* Height of mobile topbar */
            border-radius: 0 0 20px 20px !important;
            margin-top: -8px !important; /* Pull up to touch header if needed */
        }
        
        .space-y-3 > * + * {
            margin-top: 0.25rem;
        }
        
        .mb-4, .mb-3, .mb-2 {
            margin-bottom: 0.15rem !important;
        }
        
        .container-fluid {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        
        .fade-in-up {
            padding-top: 0 !important;
        }
        
        .row.g-3 {
            margin-top: 0 !important;
            row-gap: 0.15rem !important;
        }
    }
</style>
@endpush

@endsection
