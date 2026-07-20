@extends('layouts.doctor')

@section('content')

<style>
    .prescription-paper {
        max-width: 900px;
        margin: 0 auto 40px;
        padding-bottom: 0;
        background: #fff;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .prescription-paper > .p-4,
    .prescription-paper > .p-md-5 {
        flex: 1;
    }

    .hospital-banner {
        background: var(--primary-gradient);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .section-header {
        background: #f8fafc;
        padding: 8px 16px;
        font-weight: 700;
        color: var(--primary-color);
        border-left: 4px solid var(--primary-color);
        margin: 12px 0 10px;
        font-size: 0.95rem;
    }

    .rx-symbol {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        font-family: 'Times New Roman', Times, serif;
        margin-right: 10px;
    }

    .action-footer {
        position: static;
        background: transparent;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        padding: 8px 0 0 0;
        border-top: 1px solid #e2e8f0;
        z-index: auto;
        box-shadow: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .action-footer .container {
        max-width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0 !important;
        gap: 0.5rem;
        width: 100%;
    }
    .save-btn {
        white-space: nowrap;
        min-height: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        border-radius: 18px !important;
        padding: 0 10px !important;
        font-size: 0.8rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    .medicine-row {
        transition: var(--transition);
        border-radius: 10px;
    }
    .medicine-row:hover {
        background: #f8fafc;
    }

    /* Medicine Search Dropdown */
    .search-results-container {
        position: relative;
    }
    #search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 12px 12px;
        box-shadow: var(--shadow-lg);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1100;
        display: none;
    }
    .search-result-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        transition: var(--transition);
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-item:hover {
        background: #f8fafc;
    }
    .search-result-item .med-name {
        font-weight: 600;
        color: var(--text-dark);
        display: block;
    }
    .search-result-item .med-info {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    /* Interactive Dose Badges */
    .dose-badge {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: white;
        border: 2px solid #e2e8f0;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        user-select: none;
    }
    .dose-badge.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    /* Mobile Table Overhaul */
    @media (max-width: 768px) {
        .medicine-table-container {
            display: none;
        }
        .medicine-cards-container {
            display: block;
        }
        .medicine-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 10px 12px;
            margin-bottom: 8px;
            position: relative;
            border: 1px solid #e2e8f0;
        }
    }
    @media (min-width: 769px) {
        .medicine-table-container {
            display: block;
        }
        .medicine-cards-container {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.2rem !important;
        }
        h6 {
            font-size: 0.9rem;
        }
        .prescription-paper {
            border-radius: 12px;
            margin: 0 auto 30px;
        }
        .p-4, .px-4, .py-4 {
            padding: 8px !important;
        }
    }
</style>

<div class="prescription-paper fade-in-up">
    <div class="p-4 p-md-5">
        <!-- Patient Info -->
        <div class="row g-2 mb-2">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-light rounded-circle p-2 text-primary">
                        <i class="bi bi-person-fill h6 m-0"></i>
                    </div>
                    <div>
                        <div class="form-label mb-0">Patient Name</div>
                        <h6 class="fw-bold m-0">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-label mb-0">Age / Gender</div>
                <div class="fw-semibold" style="font-size: 0.9rem;">{{ $appointment->patient->age }}Y / {{ $appointment->patient->gender ?? 'M' }}</div>
            </div>
            <div class="col-6 col-md-3 text-end">
                <div class="form-label mb-0">Date</div>
                <div class="fw-semibold" style="font-size: 0.9rem;">{{ date('d M, Y') }}</div>
            </div>
        </div>

        <input type="hidden" id="appointment_id" value="{{ $appointment->id }}">
        <input type="hidden" id="patient_id" value="{{ $appointment->patient->id }}">

        <!-- Rx Section -->
        <div class="section-header d-flex align-items-center">
            <span class="rx-symbol">Rx</span> Prescription
        </div>

        <div class="card border-0 bg-light p-3 mb-4 rounded-4">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Search Medicine</label>
                    <div class="search-results-container">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input id="medicineInput" class="form-control border-start-0" placeholder="Start typing..." autocomplete="off">
                        </div>
                        <div id="search-results"></div>
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label">Category</label>
                    <input id="category" class="form-control bg-white-50" readonly>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label">Pattern</label>
                    <div id="interactive-dose" class="d-flex gap-1">
                        <!-- Toggles will be rendered here -->
                    </div>
                    <input type="hidden" id="dose">
                </div>

                <div class="col-7 col-md-3">
                    <label class="form-label">Start / Timing</label>
                    <select id="start_time" class="form-select">
                        @foreach($startTimes as $t)
                            <option value="{{ $t->id }}" data-mr="{{ $t->name_mr }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-5 col-md-1">
                    <label class="form-label">Quantity</label>
                    <input id="days" type="number" class="form-control" min="1" value="3">
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary px-4 rounded-pill" onclick="addMedicine()">
                        <i class="bi bi-plus-lg me-1"></i> Add to List
                    </button>
                </div>
            </div>
        </div>

        <!-- Added Medicines -->
        <div class="medicine-table-container">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted small text-uppercase fw-bold">
                        <th class="border-0">#</th>
                        <th class="border-0">Medicine Name</th>
                        <th class="border-0">Dosage Pattern</th>
                        <th class="border-0">Timing</th>
                        <th class="border-0 text-center">Qty</th>
                        <th class="border-0 text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="medicineRows">
                    <!-- Dynamic Rows (Desktop) -->
                </tbody>
            </table>
        </div>

        <div class="medicine-cards-container">
            <div id="medicineCards">
                <!-- Dynamic Cards (Mobile) -->
            </div>
        </div>

        <!-- Clinical Notes -->
        <div class="section-header">Clinical Notes & Advice</div>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label">Diagnosis / Complaints</label>
                <textarea id="diagnosis" class="form-control rounded-4" rows="3" placeholder="Enter findings...">{{ $appointment->prescription->diagnosis ?? '' }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Treatment / Advice</label>
                <textarea id="treatment" class="form-control rounded-4" rows="3" placeholder="Enter advice for patient...">{{ $appointment->prescription->treatment ?? '' }}</textarea>
            </div>
        </div>
        
        <!-- Action Footer - Inside Card -->
        <div class="action-footer" style="border-top: 1px solid #e2e8f0; padding: 8px 0 0 0;">
            <div class="container px-3" style="padding: 0;">
                <a href="{{ url()->previous() ?: route('doctor.dashboard') }}" class="btn btn-link text-muted text-decoration-none fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Cancel
                </a>
                <button class="btn btn-primary btn-lg px-4 px-md-5 shadow-lg save-btn" onclick="saveAndPrint()">
                    <i class="bi bi-printer-fill me-2"></i> Save & Print Rx
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let medicineMap = {};
let medicines = [];
let counter = 1;

const medicineInput = document.getElementById('medicineInput');
const category = document.getElementById('category');
const dose = document.getElementById('dose');
const startTime = document.getElementById('start_time');
const days = document.getElementById('days');

/* INITIALIZE IF EDITING */
@if($appointment->prescription && $appointment->prescription->medicines)
    @foreach($appointment->prescription->medicines as $m)
        medicines.push({
            name: "{{ $m->medicine_name }}",
            category: "{{ $m->category }}",
            dose: "{{ $m->dose }}",
            start: "{{ $m->start_time }}",
            start_mr: "{{ $m->start_time_mr }}",
            days: "{{ $m->days }}"
        });
    @endforeach
    renderMedicines();
@endif

function renderMedicines() {
    const tbody = document.getElementById('medicineRows');
    const cards = document.getElementById('medicineCards');
    tbody.innerHTML = '';
    cards.innerHTML = '';
    counter = 1;
    
    medicines.forEach((medData, index) => {
        // Table Row (Desktop)
        const row = `
            <tr class="medicine-row fade-in" id="row-${counter}">
                <td class="fw-bold text-muted">${counter}</td>
                <td>
                    <div class="fw-bold">${medData.name}</div>
                    <div class="small text-muted">${medData.category}</div>
                </td>
                <td><span class="badge bg-light text-dark fw-normal border px-3 py-1">${medData.dose}</span></td>
                <td>${medData.start_mr || medData.start}</td>
                <td class="text-center fw-bold">${medData.days}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeMedicine(${index})">
                        <i class="bi bi-trash3"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
        
        // Card (Mobile)
        const cardHtml = `
            <div class="medicine-card fade-in">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold text-primary">${medData.name}</div>
                        <div class="small text-muted">${medData.category}</div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeMedicine(${index})">
                        <i class="bi bi-trash3 h5"></i>
                    </button>
                </div>
                <div class="d-flex gap-2 align-items-center mt-3">
                    <span class="badge bg-white shadow-sm text-dark border px-3 py-2" style="font-size: 0.9rem;">${medData.dose}</span>
                    <span class="small text-muted">|</span>
                    <span class="small fw-semibold text-muted">${medData.start_mr || medData.start}</span>
                    <div class="ms-auto bg-primary text-white px-3 py-1 rounded-pill small fw-bold">Qty: ${medData.days}</div>
                </div>
            </div>
        `;
        cards.insertAdjacentHTML('beforeend', cardHtml);
        
        counter++;
    });
}

/* SEARCH MEDICINE WITH DROPDOWN */
let searchTimeout;
medicineInput.addEventListener('input', function () {
    const term = this.value;
    const resultsDiv = document.getElementById('search-results');
    
    if (term.length < 1) {
        resultsDiv.style.display = 'none';
        return;
    }

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetch(`/ajax/medicines?q=${term}`)
            .then(res => res.json())
            .then(data => {
                medicineMap = {};
                if (data.length === 0) {
                    resultsDiv.style.display = 'none';
                    return;
                }

                let html = '';
                data.forEach(m => {
                    medicineMap[m.name.toLowerCase()] = m;
                    html += `
                        <div class="search-result-item" onclick="selectMedicine('${m.name.replace(/'/g, "\\'")}')">
                            <span class="med-name">${m.name}</span>
                        </div>
                    `;
                });
                resultsDiv.innerHTML = html;
                resultsDiv.style.display = 'block';
            });
    }, 300);
});

function renderInteractiveDose(pattern) {
    const container = document.getElementById('interactive-dose');
    const doseInput = document.getElementById('dose');
    doseInput.value = pattern;
    
    // Split by non-digit characters (like - or space) or just take digits
    const digits = pattern.split(/[^0-9]/).filter(d => d.length > 0);
    
    let html = '';
    digits.forEach((digit, index) => {
        const isActive = digit === '1';
        html += `
            <div onclick="toggleDoseDigit(${index})" 
                 class="dose-badge ${isActive ? 'active' : ''}">
                ${digit}
            </div>
            ${index < digits.length - 1 ? '<span class="text-muted opacity-50 d-flex align-items-center mb-1 fw-bold">-</span>' : ''}
        `;
    });
    container.innerHTML = html;
}

function toggleDoseDigit(index) {
    const doseInput = document.getElementById('dose');
    let pattern = doseInput.value;
    const digits = pattern.split(/[^0-9]/).filter(d => d.length > 0);
    const separators = pattern.split(/[0-9]/).filter(s => s !== '');

    digits[index] = digits[index] === '1' ? '0' : '1';
    
    // Reconstruct pattern
    let newPattern = '';
    for(let i=0; i<digits.length; i++) {
        newPattern += digits[i];
        if (i < separators.length) {
            newPattern += separators[i];
        } else if (i < digits.length - 1) {
            newPattern += '-';
        }
    }
    
    renderInteractiveDose(newPattern);
}

function selectMedicine(name) {
    const med = medicineMap[name.toLowerCase()];
    if (!med) return;

    medicineInput.value = med.name;
    category.value = med.category ? med.category.name : '';
    
    const initialPattern = med.dose ? med.dose.pattern : '0-0-0';
    renderInteractiveDose(initialPattern);
    
    startTime.value = med.start_time ? med.start_time.id : '';
    
    document.getElementById('search-results').style.display = 'none';
    days.focus();
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!medicineInput.contains(e.target) && !document.getElementById('search-results').contains(e.target)) {
        document.getElementById('search-results').style.display = 'none';
    }
});

/* ADD MEDICINE */
function addMedicine() {
    if (!medicineInput.value) {
        alert('Please select a medicine');
        return;
    }

    const selectedOption = startTime.options[startTime.selectedIndex];
    const medData = {
        name: medicineInput.value,
        category: category.value,
        dose: dose.value,
        start: selectedOption.text,
        start_mr: selectedOption.getAttribute('data-mr') || '',
        days: days.value
    };

    medicines.push(medData);
    renderMedicines();

    // Reset inputs
    medicineInput.value = '';
    category.value = '';
    dose.value = '';
    medicineInput.focus();
}

function removeMedicine(index) {
    medicines.splice(index, 1);
    renderMedicines();
}

/* SAVE & PRINT */
function saveAndPrint() {
    if (medicines.length === 0) {
        alert('Add at least one medicine to the prescription');
        return;
    }

    const btn = event.currentTarget;
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

    fetch("{{ route('prescription.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({
            appointment_id: document.getElementById('appointment_id').value,
            patient_id: document.getElementById('patient_id').value,
            diagnosis: document.getElementById('diagnosis').value,
            treatment: document.getElementById('treatment').value,
            medicines: medicines
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.print_url) {
            window.open(data.print_url, '_blank');
            setTimeout(() => {
                window.location.href = "{{ route('doctor.dashboard') }}";
            }, 1000);
        } else {
            alert('Failed to save prescription: ' + (data.error || 'Unknown error'));
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    })
    .catch((err) => {
        alert('Network error while saving prescription');
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
}
</script>

@endsection
