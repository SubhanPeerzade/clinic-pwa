<!DOCTYPE html>
<html>
<head>
    <title>Prescription</title>
    <style>
        @page { size: A5 portrait; margin: 10mm; }
        body { font-family: 'Hind', Arial, sans-serif; font-size: 11px; }
        .header { text-align:center; border-bottom:1px solid #000; }
        .rx { margin-top:10px; }
        table { width:100%; border-collapse:collapse; }
        th, td { border:1px solid #000; padding:4px; text-align:center; }
        .footer { position:fixed; bottom:0; width:100%; border-top:1px solid #000; }
    </style>
</head>

<body>

<div class="header">
    <h3>AAROGYA CHIKITSALAYA</h3>
    <small>Behind Maruti Temple, Manerajuri | Ph: 9970254955</small>
</div>

<p><b>Name:</b> {{ $patient->first_name }} {{ $patient->last_name }}</p>
<p><b>Age:</b> {{ $patient->age }} |
   <b>Date:</b> {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</p>

<div class="rx">
    <b>Diagnosis:</b> {{ $prescription->diagnosis }} <br><br>
    <b>Treatment:</b> {{ $prescription->treatment }}
</div>

<h4>Rx</h4>

@php
    $abbrMap = [
        'tablet' => 'Tab',
        'syrup' => 'Syp',
        'capsule' => 'Cap',
        'injection' => 'Inj',
        'cream' => 'Crm',
        'ointment' => 'Ont',
        'drops' => 'Drp',
        'powder' => 'Pwd',
        'lotion' => 'Lot',
        'gel' => 'Gel',
        'soap' => 'Soap',
        'shampoo' => 'Shp',
        'inhaler' => 'Inh',
        'spray' => 'Spy',
        'suspension' => 'Sus',
        'sachet' => 'Sac',
    ];
@endphp

@foreach($medicines as $m)
<div style="margin-bottom: 12px; font-family: 'Hind', Arial, sans-serif;">
    <div style="font-size: 13px;">
        <span style="margin-right: 15px;">-</span>
        <span style="margin-right: 15px; font-weight: 500;">
            {{ $abbrMap[strtolower($m->category)] ?? ucfirst($m->category) }}
        </span>
        <span style="font-weight: 700;">{{ $m->medicine_name }}</span>
        <span style="margin: 0 10px;">-</span>
        <span style="border: 1px solid #000; border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; line-height: 1; vertical-align: middle;">
            {{ $m->days }}
        </span>
    </div>
    <div style="margin-left: 65px; margin-top: 3px; font-size: 12px;">
        <span style="margin-right: 40px;">{{ $m->dose }}</span>
        <span>{{ $m->start_time_mr ?: $m->start_time }}</span>
    </div>
</div>
@endforeach

<div class="footer">
    <p><b>Doctor Signature</b></p>
</div>

<script>
    window.print();
</script>

</body>
</html>
