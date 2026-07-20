@extends('layouts.app')

@php
    $showBackButton = true;
    $pageTitle = 'Pending Patients Today';
@endphp

@section('content')
<div class="row g-4 px-1 fade-in">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold m-0 text-primary">Pending Queue</h3>
                <p class="text-muted small m-0">{{ $pending->count() }} patients waiting to be checked-in</p>
            </div>
            <a href="{{ route('reception.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-grid-1x2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="col-12 mt-2 fade-in-up">
        @if($pending->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                <i class="bi bi-clock-history text-muted opacity-25" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No pending appointments</h5>
                <p class="small text-muted mb-0">The pending queue is currently empty for today</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($pending as $a)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-3 {{ $a->is_not_present ? 'bg-danger-subtle' : 'bg-white' }} hover-scale" id="appt-card-{{ $a->id }}">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-minor" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                            {{ $a->token }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark h6 m-0">{{ $a->patient_name }}</div>
                                            <div class="small text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-telephone"></i> {{ $a->patient_phone }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-danger text-white remark-badge {{ $a->remark ? '' : 'd-none' }}" id="remark-display-{{ $a->id }}" style="font-size: 0.7rem;">
                                        {{ $a->remark }}
                                    </span>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="d-flex gap-2 mb-2">
                                        <button class="btn {{ $a->is_not_present ? 'btn-danger' : 'btn-outline-danger' }} btn-sm rounded-pill not-present-btn px-3 py-1 flex-grow-1" data-appt-id="{{ $a->id }}" style="font-size: 0.75rem;">
                                            <i class="bi bi-person-x me-1"></i> Not Present
                                        </button>
                                        
                                        <button class="btn btn-outline-secondary btn-sm rounded-pill remark-btn px-3 py-1 {{ $a->is_not_present ? '' : 'd-none' }} flex-grow-1" data-appt-id="{{ $a->id }}" data-remark="{{ $a->remark }}" style="font-size: 0.75rem;">
                                            <i class="bi bi-chat-left-text me-1"></i> Remark
                                        </button>
                                    </div>
                                    <button class="btn btn-premium btn-sm rounded-pill checkin-btn px-4 py-1 w-100" data-appt-id="{{ $a->id }}" style="font-size: 0.75rem;">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Check-in
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('modals')
<!-- Remark Modal -->
<div class="modal fade" id="remarkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-primary m-0">Add Remark</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-appt-id">
                <div class="mb-3">
                    <label for="remark-textarea" class="form-label small text-muted">Enter remark for this patient</label>
                    <textarea class="form-control rounded-3 border-light-subtle shadow-sm" id="remark-textarea" rows="3" placeholder="e.g. Patient not responding, went home, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="save-remark-btn">Save Remark</button>
            </div>
        </div>
    </div>
</div>
@endpush

<style>
    .space-y-3 > * + * { margin-top: 1rem; }
    .hover-scale { transition: var(--transition); }
    .hover-scale:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .shadow-minor { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>

@push('scripts')
<script>
/* CHECK-IN HANDLER */
document.addEventListener("click", e => {
    const btn = e.target.closest(".checkin-btn");
    if (!btn) return;
    const id = btn.dataset.apptId;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch(`/appointments/${id}/checkin`, {
        method: "POST",
        headers: { "Content-Type":"application/json", "Accept":"application/json", "X-CSRF-TOKEN":"{{ csrf_token() }}" }
    })
    .then(res => res.json())
    .then(() => {
        window.location.reload();
    })
    .catch(() => { alert("Check-in failed"); btn.disabled = false; btn.innerText = "Check-in"; });
});

/* NOT PRESENT HANDLER */
document.addEventListener("click", e => {
    const btn = e.target.closest(".not-present-btn");
    if (!btn) return;
    const id = btn.dataset.apptId;
    
    fetch(`/appointments/${id}/toggle-not-present`, {
        method: "POST",
        headers: { "Content-Type":"application/json", "Accept":"application/json", "X-CSRF-TOKEN":"{{ csrf_token() }}" }
    })
    .then(res => res.json())
    .then(data => {
        const card = document.getElementById(`appt-card-${id}`);
        const remarkBtn = card.querySelector(".remark-btn");
        if (data.is_not_present) {
            card.classList.remove('bg-white');
            card.classList.add('bg-danger-subtle');
            btn.classList.add('btn-danger');
            btn.classList.remove('btn-outline-danger');
            remarkBtn.classList.remove('d-none');
        } else {
            card.classList.add('bg-white');
            card.classList.remove('bg-danger-subtle');
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
            remarkBtn.classList.add('d-none');
        }
    })
    .catch(() => { alert("Update failed"); });
});

/* REMARK MODAL HANDLER */
const remarkModal = new bootstrap.Modal(document.getElementById('remarkModal'));
document.addEventListener("click", e => {
    const btn = e.target.closest(".remark-btn");
    if (!btn) return;
    document.getElementById('modal-appt-id').value = btn.dataset.apptId;
    document.getElementById('remark-textarea').value = btn.dataset.remark || '';
    remarkModal.show();
});

/* SAVE REMARK HANDLER */
document.getElementById('save-remark-btn').addEventListener("click", () => {
    const id = document.getElementById('modal-appt-id').value;
    const remark = document.getElementById('remark-textarea').value;
    const btn = document.getElementById('save-remark-btn');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch(`/appointments/${id}/remark`, {
        method: "POST",
        headers: { "Content-Type":"application/json", "Accept":"application/json", "X-CSRF-TOKEN":"{{ csrf_token() }}" },
        body: JSON.stringify({ remark: remark })
    })
    .then(res => res.json())
    .then(data => {
        const display = document.getElementById(`remark-display-${id}`);
        const remarkBtn = document.querySelector(`.remark-btn[data-appt-id="${id}"]`);
        display.innerText = data.remark || '';
        
        if (data.remark) {
            display.classList.remove('d-none');
        } else {
            display.classList.add('d-none');
        }
        
        remarkBtn.dataset.remark = data.remark || '';
        remarkModal.hide();
        btn.disabled = false;
        btn.innerText = "Save Remark";
    })
    .catch(() => { alert("Remark update failed"); btn.disabled = false; btn.innerText = "Save Remark"; });
});
</script>
@endpush
@endsection
