@extends('layouts.doctor')

@php
    $pageTitle = 'Seen Patients Today';
@endphp

@section('content')
<div class="row g-4 px-1 fade-in">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold m-0 text-info">Seen Patients</h3>
                <p class="text-muted small m-0">{{ $seen->count() }} checkups completed today</p>
            </div>
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-grid-1x2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="col-12 mt-2 fade-in-up">
        @if($seen->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                <i class="bi bi-people text-muted opacity-25" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No patients seen yet</h5>
                <p class="small text-muted mb-0">No patient consultations have been completed today</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($seen as $appt)
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white hover-scale">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-minor" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ $appt->token }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark h6 m-0">{{ $appt->patient_name }}</div>
                                    <div class="small text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-clock"></i> Seen at {{ \Carbon\Carbon::parse($appt->updated_at)->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap justify-content-end">
                                <a href="{{ route('doctor.history', $appt->patient_id) }}" class="btn btn-outline-secondary rounded-pill px-3">
                                    <i class="bi bi-clock-history me-1"></i> History
                                </a>
                                @if($appt->prescription)
                                    <button type="button" class="btn btn-outline-info rounded-pill px-3 view-rx-btn shadow-sm" data-rx-id="{{ $appt->prescription->id }}">
                                        <i class="bi bi-eye me-1"></i> View Rx
                                    </button>
                                @endif
                                <a href="{{ route('doctor.prescription.edit', $appt->id) }}" class="btn btn-premium rounded-pill px-4">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Rx
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .space-y-3 > * + * { margin-top: 1rem; }
    .hover-scale { transition: var(--transition); }
    .hover-scale:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .shadow-minor { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    @media (max-width: 768px) {
        .d-flex.gap-2.flex-wrap.justify-content-end {
            gap: 0.5rem !important;
        }
        
        .btn.btn-outline-secondary,
        .btn.btn-outline-info,
        .btn.btn-premium {
            padding: 6px 12px !important;
            font-size: 0.85rem;
            min-height: auto;
        }
        
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }
    }
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
@endpush
@endsection
