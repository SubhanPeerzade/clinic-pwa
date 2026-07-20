<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription</title>

    <!-- Google Fonts for Marathi (Devanagari) and English -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ================= RESET & BASE ================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Noto Sans Devanagari', sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
            background: #fff;
        }

        /* ================= PAGE SETUP ================= */
        @page {
            size: A5 portrait;
            margin: 5mm 10mm; /* Reduced top/bottom margin */
        }

        .page {
            width: 148mm; /* A5 Width */
            min-height: 190mm; /* Reduced to fit within printable area */
            margin: 0 auto;
            padding: 5mm 10mm; /* Match margins */
            position: relative;
            background: white;
            display: flex;
            flex-direction: column;
            box-sizing: border-box; /* Ensure padding doesn't add to height */
        }

        /* Hide anything outside the page container for screen preview if needed */
        @media screen {
            body {
                background: #f0f0f0;
                padding: 20px;
            }
            .page {
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .no-print {
                display: block;
                text-align: center;
                margin-bottom: 20px;
            }
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .page {
                box-shadow: none;
                margin: 0;
                width: 100% !important;
                height: 100% !important;
            }
            .no-print {
                display: none !important;
            }
        }

        /* ================= HEADER ================= */
        .clinic-header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .clinic-name {
            font-size: 18pt;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .clinic-address {
            font-size: 10pt;
            line-height: 1.3;
        }

        /* ================= RX & CONTENT ================= */
        .rx-title {
            font-size: 16pt;
            font-weight: 700;
            margin: 15px 0 10px;
        }

        .patient-info table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .patient-info td {
            padding: 4px 0;
            font-size: 11pt;
        }

        .label {
            font-weight: 700;
        }

        /* ================= MEDICINES ================= */
        .medicine-list {
            flex-grow: 1;
            max-width: 500px; /* Keep layout compact and not cover whole page */
        }

        .medicine-item {
            margin-bottom: 12px;
        }

        .med-row {
            display: flex;
            align-items: flex-start;
        }

        .med-col-cat {
            width: 50px;
            flex-shrink: 0;
            font-size: 11pt;
            font-weight: 600;
        }

        .med-col-main {
            width: 280px;
            flex-shrink: 0;
            padding-right: 15px;
        }

        .med-col-end {
            width: 120px;
            flex-shrink: 0;
            text-align: left;
        }

        .med-name {
            font-size: 12pt;
            font-weight: 700;
        }

        .med-qty {
            font-size: 11pt;
            font-weight: 600;
        }

        .med-dose {
            font-size: 10.5pt;
            color: #333;
        }

        .med-start {
            font-family: 'Noto Sans Devanagari', sans-serif;
            font-size: 10.5pt;
            color: #333;
        }

        /* ================= FOOTER ================= */
        .footer {
            margin-top: auto;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .doctor-row {
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
        }

        .doctor-block {
            line-height: 1.4;
        }
    </style>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>

<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; border-radius: 5px; background: #007bff; color: white; border: none; font-weight: bold;">
            If print dialog doesn't open, click here
        </button>
    </div>

    <div class="page">
        <!-- ================= HEADER ================= -->
        <div class="clinic-header">
            <div class="clinic-name">AAROGYA CHIKITSALAYA</div>
            <div class="clinic-address">
                Behind Maruti Temple, AP-Manerajuri, Tal-Tasgaon, Dist-Sangli<br>
                Ph: 9970254955
            </div>
        </div>

    <!-- ================= DATE & PATIENT DETAILS ================= -->
    <div class="patient-info">
        <table>
            <tr>
                <td style="text-align:left;">
                    <span class="label">Name :-</span>
                    {{ $patient->first_name }} {{ $patient->last_name ?? '' }}
                </td>
                <td style="text-align:right;">
                    <span class="label">Date :</span> {{ $date ?? now()->format('d-m-Y') }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left;">
                    <span class="label">Age :</span> {{ $patient->age }}
                    <span style="display: inline-block; width: 60px;"></span>
                    <span class="label">Mobile No :</span> {{ $patient->phone }}
                </td>
            </tr>
        </table>
    </div>

    <!-- ================= RX ================= -->
    <div class="rx-title">Rx</div>

    <div class="medicine-list">
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

        @foreach($medicines as $med)
        <div class="medicine-item">
            <div class="med-row">
                <div class="med-col-cat">
                    {{ $abbrMap[strtolower($med->category)] ?? ucfirst($med->category) }}
                </div>
                <div class="med-col-main med-name">
                    {{ $med->medicine_name }}
                </div>
                <div class="med-col-end med-qty">
                    {{ $med->days }}
                </div>
            </div>
            <div class="med-row">
                <div class="med-col-cat"></div>
                <div class="med-col-main med-dose">
                    {{ $med->dose }}
                </div>
                <div class="med-col-end med-start">
                    {{ $med->start_time_mr ?: $med->start_time }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        <div class="doctor-row">
            <div class="doctor-block">
                <strong>Dr. Sandeep Kharkande</strong><br>
                B.A.M.S.<br>
                Regd. No. I-25592 A-1
            </div>

            <div class="doctor-block" style="text-align:right;">
                <strong>Dr. Shailaja Kharkande</strong><br>
                B.A.M.S.<br>
                Regd. No. I-24965 A-1
            </div>
        </div>
    </div>

</div>

</body>
</html>
