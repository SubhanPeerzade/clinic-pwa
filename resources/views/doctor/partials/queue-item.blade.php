<div class="list-group-item p-3 queue-item-card border-0 border-bottom">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="{{ $type === 'pending' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }} token-sm">
                {{ $appt->token }}
            </div>
            <div>
                <div class="fw-bold text-dark small">{{ $appt->patient_name }}</div>
                <div class="small text-muted" style="font-size: 0.75rem;">{{ $appt->patient_phone }}</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('doctor.history', $appt->patient_id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2" style="font-size: 0.7rem;" title="History">
                <i class="bi bi-clock-history"></i>
            </a>
            @if($appt->status !== 'seen')
                <a href="{{ route('doctor.prescription', $appt->id) }}" class="btn btn-sm btn-primary rounded-pill px-3" style="font-size: 0.75rem;">
                    Add Rx
                </a>
            @else
                <a href="{{ route('prescription.view', $appt->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3" style="font-size: 0.75rem;">
                    View
                </a>
            @endif
        </div>
    </div>
</div>
