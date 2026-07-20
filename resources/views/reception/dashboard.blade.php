@extends(auth()->user()->role === 'doctor' ? 'layouts.doctor' : 'layouts.app')

@php
    $hideTopbarActions = true;
    $pageTitle = 'Reception';
@endphp

@section('content')

@if(auth()->user()->role === 'doctor')
    <div class="card border-0 shadow-sm mb-4 bg-white overflow-hidden">
        <div class="card-body p-3">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm">
                    <i class="bi bi-shield-check me-1"></i> Admin
                </a>
                <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm">
                    <i class="bi bi-stethoscope me-1"></i> Doctor
                </a>
                <a href="{{ route('reception.dashboard') }}" class="btn btn-primary rounded-pill px-4 btn-sm active">
                    <i class="bi bi-headset me-1"></i> Reception
                </a>
            </div>
        </div>
    </div>
@endif

<div class="row g-4">
    <!-- Header Area -->
    <div class="col-12 fade-in">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold m-0 text-primary">Reception Desk</h3>
                <p class="text-muted m-0 small">Search patients and manage today's queue</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.daily') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm fw-600">
                    <i class="bi bi-file-earmark-text me-1"></i> Reports
                </a>
                <a href="{{ route('patients.create') }}" class="btn btn-premium rounded-pill px-4 shadow-sm fw-600">
                    <i class="bi bi-person-plus-fill me-1"></i> New Patient
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN SEARCH PANEL -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-search text-primary"></i> Patient Search
            </h5>
            
            <form id="searchForm" action="{{ route('reception.search') }}" method="get" class="mb-4">
                <div class="search-container shadow-sm rounded-4 border p-1 bg-white">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input id="searchInput" name="q" type="search" class="form-control border-0 py-3 rounded-pill"
                               placeholder="Name, phone, or ID..." autocomplete="off" style="box-shadow: none;">
                        <button id="clearBtn" type="button" class="btn btn-light border-0 text-muted px-3"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
            </form>

            <div id="resultsArea">
                <div id="cardList" class="space-y-3">
                    @if(isset($patients) && $patients->count())
                        @foreach($patients as $p)
                            <div class="card border shadow-none mb-3 rounded-4 p-3 hover-bg">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: 700;">
                                            {{ strtoupper(substr($p->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $p->first_name }} {{ $p->last_name }}</div>
                                            <div class="small text-muted">ID: {{ $p->patient_id }} • {{ $p->phone }}</div>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-light rounded-circle p-2" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3">
                                            <li><a class="dropdown-item rounded-2" href="{{ route('patients.show',$p->id) }}"><i class="bi bi-eye me-2"></i> View</a></li>
                                            <li><a class="dropdown-item rounded-2 text-warning" href="{{ route('patients.edit',$p->id) }}"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item rounded-2 text-primary fw-bold" href="{{ route('appointments.create',['patient_id'=>$p->id]) }}"><i class="bi bi-calendar-plus me-2"></i> Select Patient</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-person-vcard text-muted opacity-25" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Start typing to search for patients</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- QUEUE PANEL -->
    <div class="col-lg-5 fade-in-up">
        <div class="d-flex flex-column gap-4 h-100">
            
            <!-- PENDING QUEUE -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-warning"></i> Pending Queue
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3" id="pendingCount">0</span>
                        <a href="{{ route('reception.pending') }}" class="btn btn-link btn-sm text-decoration-none p-0 fw-600">View All</a>
                    </div>
                </div>
                <div class="card-body p-4 pt-1" id="pendingQueueArea">
                    <div class="text-center py-5 opacity-50">
                        <div class="spinner-border text-primary spinner-border-sm mb-3"></div>
                        <p class="small m-0">Synchronizing patients...</p>
                    </div>
                </div>
            </div>

            <!-- ARRIVED/CHECKED-IN PATIENTS -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white flex-grow-1">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Arrived Patients
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success-subtle text-success rounded-pill px-3" id="arrivedCount">0</span>
                        <a href="{{ route('reception.arrived') }}" class="btn btn-link btn-sm text-decoration-none p-0 fw-600 text-success">View All</a>
                    </div>
                </div>
                <div class="card-body p-4 pt-1" id="arrivedQueueArea">
                    <div class="text-center py-5 opacity-50">
                        <i class="bi bi-person-x h1 d-block mb-3"></i>
                        <p class="small m-0">No patients arrived yet</p>
                    </div>
                </div>
            </div>

        </div>
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
    .hover-bg:hover { background-color: #f8fafc; border-color: var(--primary-color) !important; }
    .space-y-3 > * + * { margin-top: 1rem; }
    .search-container:focus-within { border-color: var(--primary-color) !important; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important; }
    
    @media (max-width: 768px) {
        .btn-sm-wide { width: 100%; border-radius: 12px !important; }
        .queue-card { margin-bottom: 6px; }
        
        .btn.w-100, button.w-100 {
            width: calc(100% - 0px) !important;
            box-sizing: border-box;
            padding: 6px 8px !important;
            font-size: 0.85rem;
        }
        
        .btn-primary.w-100 {
            padding: 6px 8px !important;
        }
        
        .row.g-4 {
            margin-left: 0 !important;
            margin-right: 0 !important;
            gap: 0.75rem !important;
        }
        
        .col-lg-7,
        .col-lg-5 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }
    }
</style>

@endsection

@push('scripts')
<script>
/* STATUS COLORS */
function statusClass(s) {
    switch (s) {
        case "waiting": return "bg-light text-muted border";
        case "arrived": return "bg-success-subtle text-success border-success-subtle";
        case "called": return "bg-warning-subtle text-warning border-warning-subtle";
        case "seen": return "bg-dark text-white";
        default: return "bg-light text-dark";
    }
}

function escapeHtml(s) {
    return s ? s.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;") : "";
}

/* FETCH TODAY QUEUE */
async function fetchTodayQueue() {
    try {
        const url = new URL("{{ route('appointments.today') }}", window.location.origin);
        const res = await fetch(url, { headers: { "Accept":"application/json" } });
        return await res.json();
    } catch (e) { return null; }
}

/* RENDER QUEUE */
function renderQueue(data) {
    const pendingArea = document.getElementById("pendingQueueArea");
    const arrivedArea = document.getElementById("arrivedQueueArea");
    
    if (!data || !data.queue) return;

    const pending = data.queue.filter(a => a.status === "waiting");
    const arrived = data.queue.filter(a => a.status !== "waiting");

    document.getElementById("pendingCount").innerText = pending.length;
    document.getElementById("arrivedCount").innerText = arrived.length;

    // Show only latest 5
    const pendingToDisplay = pending.slice(0, 5);
    
    // Sort arrived by checked-in time (arrived_at) DESC - latest first
    const arrivedSorted = arrived.sort((a, b) => {
        const dateA = a.arrived_at ? new Date(a.arrived_at) : 0;
        const dateB = b.arrived_at ? new Date(b.arrived_at) : 0;
        return dateB - dateA;
    });
    const arrivedToDisplay = arrivedSorted.slice(0, 5);

    // Render Pending
    if (pendingToDisplay.length === 0) {
        pendingArea.innerHTML = `<div class="text-center py-4 opacity-50 small">No pending patients</div>`;
    } else {
        pendingArea.innerHTML = pendingToDisplay.map(a => `
            <div class="card border shadow-none rounded-4 mb-3 p-3 queue-card ${a.is_not_present ? 'bg-danger-subtle' : ''}" id="appt-card-${a.id}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-weight: 800; font-size: 0.9rem;">
                            ${a.token}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="fw-bold text-dark">${escapeHtml(a.patient_name)}</div>
                                <span class="badge bg-danger text-white remark-badge ${a.remark ? '' : 'd-none'}" id="remark-display-${a.id}" style="font-size: 0.65rem;">
                                    ${escapeHtml(a.remark)}
                                </span>
                            </div>
                            <div class="small text-muted">${escapeHtml(a.patient_phone)}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex flex-wrap gap-2 w-100">
                        <button class="btn ${a.is_not_present ? 'btn-danger' : 'btn-outline-danger'} btn-sm rounded-pill not-present-btn px-3 py-1 flex-grow-1" data-appt-id="${a.id}" style="font-size: 0.7rem; min-width: 100px;">
                            <i class="bi bi-person-x"></i> Not Present
                        </button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill remark-btn px-3 py-1 ${a.is_not_present ? '' : 'd-none'} flex-grow-1" data-appt-id="${a.id}" data-remark="${escapeHtml(a.remark)}" style="font-size: 0.7rem; min-width: 80px;">
                            <i class="bi bi-chat-left-text"></i> Remark
                        </button>
                        <button class="btn btn-primary btn-sm rounded-pill checkin-btn py-1 fw-bold flex-grow-1" data-appt-id="${a.id}" style="font-size: 0.8rem; min-width: 100px;">
                            <i class="bi bi-box-arrow-in-right"></i> Check-in
                        </button>
                    </div>
                </div>
            </div>
        `).join("");
    }

    // Render Arrived
    if (arrivedToDisplay.length === 0) {
        arrivedArea.innerHTML = `<div class="text-center py-4 opacity-50 small">No patients in the arrived list</div>`;
    } else {
        arrivedArea.innerHTML = arrivedToDisplay.map(a => `
            <div class="card border shadow-none border-success-subtle bg-success-light rounded-4 mb-2 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 800; font-size: 0.8rem;">
                            ${a.token}
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">${escapeHtml(a.patient_name)}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">${a.status.toUpperCase()}</div>
                        </div>
                    </div>
                    <i class="bi bi-check-circle-fill text-success h5 m-0"></i>
                </div>
            </div>
        `).join("");
    }
}

async function refreshQueueNow() {
    const data = await fetchTodayQueue();
    renderQueue(data);
}

refreshQueueNow();
setInterval(refreshQueueNow, 10000);

/* CHECK-IN HANDLER */
document.addEventListener("click", e => {
    const btn = e.target.closest(".checkin-btn");
    if (!btn) return;
    const id = btn.dataset.apptId;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>...';

    fetch(`/appointments/${id}/checkin`, {
        method: "POST",
        headers: { "Content-Type":"application/json", "Accept":"application/json", "X-CSRF-TOKEN":"{{ csrf_token() }}" }
    })
    .then(res => res.json())
    .then(() => refreshQueueNow())
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
        refreshQueueNow();
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
        refreshQueueNow();
        remarkModal.hide();
        btn.disabled = false;
        btn.innerText = "Save Remark";
    })
    .catch(() => { alert("Remark update failed"); btn.disabled = false; btn.innerText = "Save Remark"; });
});

/* SEARCH */
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("searchInput");
    const clearBtn = document.getElementById("clearBtn");
    const cardList = document.getElementById("cardList");
    let debounceTimer;

    async function doSearch(q) {
        if (!q.trim()) {
            cardList.innerHTML = `<div class="text-center py-5"><i class="bi bi-person-vcard text-muted opacity-25" style="font-size: 4rem;"></i><p class="text-muted mt-3">Start typing to search for patients</p></div>`;
            return;
        }

        const url = new URL("{{ route('reception.search') }}", window.location.origin);
        url.searchParams.set("q", q);
        const res = await fetch(url, { headers: { "Accept":"application/json" } });
        const data = await res.json();

        if (data.count === 0) {
            cardList.innerHTML = `<div class="alert alert-warning border-0 rounded-4 p-4 text-center"><i class="bi bi-exclamation-triangle h4 d-block"></i> No patients found</div>`;
            return;
        }

        cardList.innerHTML = data.patients.map(p => `
            <div class="card border shadow-none mb-3 rounded-4 p-3 hover-bg">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: 700;">
                            ${(p.first_name || "?").charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">${p.first_name} ${p.last_name || ""}</div>
                            <div class="small text-muted">ID: ${p.patient_id || "-"} • ${p.phone || "-"}</div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3">
                            <li><a class="dropdown-item rounded-2" href="/patients/${p.id}"><i class="bi bi-eye me-2"></i> View</a></li>
                            <li><a class="dropdown-item rounded-2 text-warning" href="/patients/${p.id}/edit"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-2 text-primary fw-bold" href="/appointments/create?patient_id=${p.id}"><i class="bi bi-calendar-plus me-2"></i> Select Patient</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        `).join("");
    }

    input.addEventListener("input", () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => doSearch(input.value), 300);
    });

    clearBtn.addEventListener("click", () => {
        input.value = "";
        doSearch("");
    });
});
</script>
@endpush
