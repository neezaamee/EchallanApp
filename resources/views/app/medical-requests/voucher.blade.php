<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1Bill Voucher - {{ $medicalRequest->psid }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.35;
        }
        .no-print-bar {
            background: #1e293b;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
        }
        .btn-print {
            background: #2563eb;
            color: #fff;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }
        .btn-download {
            background: #dc2626;
            color: #fff;
        }
        .btn-download:hover {
            background: #b91c1c;
        }
        .btn-secondary {
            background: #475569;
            color: #fff;
        }
        .btn-secondary:hover {
            background: #334155;
        }
        .voucher-sheet {
            max-width: 820px;
            margin: 25px auto;
            background: #fff;
            padding: 24px;
            border-radius: 6px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }
        .voucher-copy {
            border: 1.5px solid #0f172a;
            border-radius: 4px;
            padding: 12px 16px;
            margin-bottom: 12px;
            background: #ffffff;
            position: relative;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 8px;
        }
        .header-logo {
            width: 55px;
            text-align: left;
            vertical-align: middle;
        }
        .header-logo img {
            max-width: 50px;
            max-height: 50px;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .header-text h1 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .header-text h2 {
            font-size: 12px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 1px;
        }
        .header-text p {
            font-size: 9.5px;
            color: #475569;
            font-weight: 600;
        }
        .copy-tag {
            width: 130px;
            text-align: right;
            vertical-align: top;
        }
        .copy-badge {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: 700;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .psid-box {
            width: 100%;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 5px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }
        .psid-table {
            width: 100%;
            border-collapse: collapse;
        }
        .psid-title {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 3px;
        }
        .psid-number {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: #0f172a;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
        }
        .barcode-container {
            text-align: right;
        }
        .barcode-container svg {
            max-height: 40px;
            max-width: 100%;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .info-table td {
            padding: 4px 8px;
            font-size: 10px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .info-table .label {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            width: 18%;
        }
        .info-table .value {
            color: #0f172a;
            font-weight: 600;
            width: 32%;
        }
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .fee-table th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
            font-size: 9.5px;
            text-align: left;
            color: #334155;
        }
        .fee-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            font-size: 10px;
        }
        .fee-total {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            background: #f8fafc;
        }
        .instructions-box {
            border: 1px dashed #94a3b8;
            background: #fbfcfe;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 8px;
            font-size: 9px;
            line-height: 1.35;
        }
        .instructions-box strong {
            color: #0f172a;
        }
        .instructions-box ol {
            margin-left: 16px;
            margin-top: 3px;
        }
        .instructions-box li {
            margin-bottom: 2px;
        }
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .sign-table td {
            width: 50%;
            vertical-align: bottom;
            font-size: 9px;
            color: #475569;
            padding-top: 12px;
        }
        .cut-line {
            text-align: center;
            margin: 10px 0;
            border-bottom: 1px dashed #94a3b8;
            position: relative;
            height: 12px;
        }
        .cut-text {
            background: #fff;
            padding: 0 12px;
            position: absolute;
            top: 2px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8.5px;
            color: #64748b;
            letter-spacing: 1.5px;
        }
        .status-pill {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .status-paid {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .voucher-sheet {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            .voucher-copy {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<!-- Action Bar (Hidden when Printing) -->
<div class="no-print-bar">
    <div style="display: flex; align-items: center; gap: 12px;">
        <span style="font-weight: 700; font-size: 14px; letter-spacing: 0.5px;">1Bill Challan Slip</span>
        <span style="background: rgba(255,255,255,0.15); font-family: monospace; padding: 3px 8px; border-radius: 4px; font-size: 12px;">{{ $medicalRequest->psid }}</span>
        @if($medicalRequest->payment_status === 'paid')
            <span class="status-pill status-paid"><i class="fas fa-check-circle me-1"></i> Paid</span>
        @else
            <span class="status-pill status-unpaid"><i class="fas fa-clock me-1"></i> Unpaid</span>
        @endif
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('medical-requests.thermal', $medicalRequest->id) }}?print=1" class="btn-action" style="background: #0891b2; color: #fff;">
            <i class="fas fa-receipt"></i> Thermal Slip (80mm)
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fas fa-print"></i> Print Slip
        </button>
        <a href="{{ route('medical-requests.voucher.download', $medicalRequest->id) }}" class="btn-action btn-download">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
        <a href="{{ route('medical-requests.show', $medicalRequest->id) }}" class="btn-action btn-secondary">
            <i class="fas fa-info-circle"></i> Details
        </a>
        <a href="{{ route('medical-requests.index') }}" class="btn-action btn-secondary">
            <i class="fas fa-arrow-left"></i> Medical Requests
        </a>
    </div>
</div>

<div class="voucher-sheet">
@php
    $copies = [
        ['title' => 'Candidate Copy', 'desc' => 'To be retained by candidate'],
        ['title' => 'Medical Center Copy', 'desc' => 'To be presented at medical center desk']
    ];
@endphp

@foreach($copies as $index => $copy)
    <div class="voucher-copy">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" alt="Punjab Police Logo">
                    @elseif(file_exists(public_path('assets/img/team/ctpf-logo.png')))
                        <img src="{{ asset('assets/img/team/ctpf-logo.png') }}" alt="Punjab Police Logo">
                    @endif
                </td>
                <td class="header-text">
                    <h1>Government of the Punjab</h1>
                    <h2>City Traffic Police</h2>
                    <p>Driving License Medical Fitness Examination &bull; 1Bill Challan</p>
                </td>
                <td class="copy-tag">
                    <div class="copy-badge">{{ $copy['title'] }}</div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 3px;">{{ $copy['desc'] }}</div>
                </td>
            </tr>
        </table>

        <!-- 1Bill PSID & Barcode Box -->
        <div class="psid-box">
            <table class="psid-table">
                <tr>
                    <td style="width: 58%; vertical-align: middle;">
                        <div class="psid-title">1Bill Consumer Number (PSID)</div>
                        <div class="psid-number">{{ chunk_split($medicalRequest->psid, 4, ' ') }}</div>
                        <div style="font-size: 8.5px; color: #64748b; margin-top: 3px;">
                            Issued: <strong>{{ $medicalRequest->created_at?->format('d/m/Y h:i A') }}</strong> &bull; 
                            Validity: <strong>30 Days</strong> &bull;
                            Status: 
                            @if($medicalRequest->payment_status === 'paid')
                                <span class="status-pill status-paid">PAID</span>
                            @else
                                <span class="status-pill status-unpaid">UNPAID</span>
                            @endif
                        </div>
                    </td>
                    <td style="width: 42%; text-align: right; vertical-align: middle;">
                        <div class="barcode-container">
                            {!! $barcodeSvg !!}
                            <div style="font-size: 8px; color: #475569; letter-spacing: 1px; font-family: monospace; margin-top: 2px;">
                                *{{ $medicalRequest->psid }}*
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Candidate Details Table -->
        <table class="info-table">
            <tr>
                <td class="label">Full Name</td>
                <td class="value">{{ $medicalRequest->citizen->full_name ?? 'N/A' }}</td>
                <td class="label">Father/Husband</td>
                <td class="value">{{ $medicalRequest->citizen->father_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">CNIC Number</td>
                <td class="value" style="font-family: monospace;">{{ $medicalRequest->citizen->cnic ?? 'N/A' }}</td>
                <td class="label">Contact Mobile</td>
                <td class="value">{{ $medicalRequest->citizen->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Gender</td>
                <td class="value">{{ ucfirst($medicalRequest->citizen->gender ?? 'N/A') }}</td>
                <td class="label">Medical Center</td>
                <td class="value">{{ $medicalRequest->medicalCenter->name ?? 'N/A' }} ({{ $medicalRequest->medicalCenter?->circle?->city?->name ?? 'Punjab' }})</td>
            </tr>
        </table>

        <!-- Fee Details Table -->
        <table class="fee-table">
            <thead>
                <tr>
                    <th style="width: 70%;">Description of Fee</th>
                    <th style="width: 30%; text-align: right;">Amount (PKR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Medical Fitness Examination & Verification Fee (Government Account)</td>
                    <td style="text-align: right; font-weight: 600;">Rs. {{ number_format($medicalRequest->amount, 2) }}</td>
                </tr>
                <tr class="fee-total">
                    <td>TOTAL PAYABLE AMOUNT: <span style="font-size: 8.5px; font-weight: normal; color: #475569;">(Rupees Two Hundred Only)</span></td>
                    <td style="text-align: right; color: #1e3a8a;">Rs. {{ number_format($medicalRequest->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 1Bill Payment Instructions -->
        <div class="instructions-box">
            <strong>1LINK 1BILL PAYMENT INSTRUCTIONS:</strong>
            <ol>
                <li><strong>Mobile Banking / Internet Banking:</strong> Go to <em>Bill Payment</em> &rarr; Select <strong>1Bill (Invoices / Vouchers)</strong> &rarr; Enter 24-digit PSID <strong>{{ $medicalRequest->psid }}</strong> &rarr; Verify Candidate Name &amp; Amount &rarr; Confirm Pay.</li>
                <li><strong>ATM / OTC / Retailers:</strong> Select 1Bill at any ATM, or provide the 24-digit PSID at any 1Link Member Bank Branch, Easypaisa, JazzCash, or TCS outlet.</li>
                <li><strong>Note:</strong> Payment updates instantly across the portal. Please carry this slip to the medical center doctor for fitness examination.</li>
            </ol>
        </div>

        <!-- Signatures -->
        <table class="sign-table">
            <tr>
                <td style="text-align: left;">
                    Candidate Signature / Thumb: _______________________
                </td>
                <td style="text-align: right;">
                    Officer / Cashier Stamp &amp; Sign: _______________________
                </td>
            </tr>
        </table>
    </div>

    @if($index === 0)
        <!-- Cut / Separation Line -->
        <div class="cut-line">
            <span class="cut-text">&bull; &bull; &bull; FOLD OR CUT HERE &bull; &bull; &bull;</span>
        </div>
    @endif
@endforeach
</div>

@if(request('print'))
<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 350);
    });
</script>
@endif

</body>
</html>

