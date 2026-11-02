<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>1Bill Voucher - {{ $medicalRequest->psid }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.3;
            background: #fff;
        }
        .voucher-copy {
            border: 1.5px solid #0f172a;
            border-radius: 4px;
            padding: 10px 14px;
            margin-bottom: 8px;
            background: #ffffff;
            position: relative;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 6px;
        }
        .header-logo {
            width: 48px;
            text-align: left;
            vertical-align: middle;
        }
        .header-logo img {
            max-width: 44px;
            max-height: 44px;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .header-text h1 {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .header-text h2 {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 1px;
        }
        .header-text p {
            font-size: 8.5px;
            color: #475569;
            font-weight: bold;
        }
        .copy-tag {
            width: 110px;
            text-align: right;
            vertical-align: top;
        }
        .copy-badge {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            padding: 3px 8px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .psid-box {
            width: 100%;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 6px;
        }
        .psid-table {
            width: 100%;
            border-collapse: collapse;
        }
        .psid-title {
            font-size: 8px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .psid-number {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #0f172a;
            font-family: 'Courier New', Courier, monospace;
        }
        .barcode-container {
            text-align: center;
            padding-top: 3px;
        }
        .barcode-container svg {
            height: 32px;
            max-width: 95%;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .info-table td {
            padding: 3px 6px;
            font-size: 9px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .info-table .label {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            width: 18%;
        }
        .info-table .value {
            color: #0f172a;
            font-weight: 600;
            width: 32%;
        }
        .section-header {
            background: #e2e8f0;
            color: #1e293b;
            font-weight: bold;
            font-size: 8.5px;
            padding: 3px 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .fee-table th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 3px 6px;
            font-size: 8.5px;
            text-align: left;
            color: #334155;
        }
        .fee-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 9px;
        }
        .fee-total {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            background: #f8fafc;
        }
        .instructions-box {
            border: 1px dashed #94a3b8;
            background: #fafafa;
            border-radius: 3px;
            padding: 5px 8px;
            margin-bottom: 6px;
            font-size: 8px;
            line-height: 1.3;
        }
        .instructions-box strong {
            color: #0f172a;
        }
        .instructions-box ol {
            margin-left: 14px;
            margin-top: 2px;
        }
        .instructions-box li {
            margin-bottom: 1px;
        }
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .sign-table td {
            width: 50%;
            vertical-align: bottom;
            font-size: 8.5px;
            color: #475569;
            padding-top: 10px;
        }
        .cut-line {
            text-align: center;
            margin: 6px 0;
            border-bottom: 1px dashed #94a3b8;
            position: relative;
            height: 10px;
        }
        .cut-text {
            background: #fff;
            padding: 0 10px;
            position: absolute;
            top: 2px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px;
            color: #64748b;
            letter-spacing: 1px;
        }
        .status-pill {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
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
    </style>
</head>
<body>

@php
    $copies = [
        ['title' => 'Candidate Copy', 'desc' => 'To be retained by the candidate'],
        ['title' => 'Medical Center Copy', 'desc' => 'To be submitted at the examination desk']
    ];
@endphp

@foreach($copies as $index => $copy)
    <div class="voucher-copy">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" alt="Logo">
                    @endif
                </td>
                <td class="header-text">
                    <h1>Government of the Punjab</h1>
                    <h2>City Traffic Police</h2>
                    <p>Driving License Medical Fitness Examination &bull; 1Bill Challan</p>
                </td>
                <td class="copy-tag">
                    <div class="copy-badge">{{ $copy['title'] }}</div>
                    <div style="font-size: 7.5px; color: #64748b; margin-top: 3px;">{{ $copy['desc'] }}</div>
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
                        <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
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
                            <div style="font-size: 7.5px; color: #475569; letter-spacing: 1px; font-family: monospace; margin-top: 1px;">
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
                    <td>TOTAL PAYABLE AMOUNT: <span style="font-size: 8px; font-weight: normal; color: #475569;">(Rupees Two Hundred Only)</span></td>
                    <td style="text-align: right; color: #1e3a8a;">Rs. {{ number_format($medicalRequest->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 1Bill Payment Instructions -->
        <div class="instructions-box">
            <strong>1LINK 1BILL PAYMENT INSTRUCTIONS:</strong>
            <ol>
                <li><strong>Mobile Banking / Internet Banking:</strong> Go to <em>Bill Payment</em> &rarr; Select <strong>1Bill (Invoices / Vouchers)</strong> &rarr; Enter 24-digit PSID <strong>{{ $medicalRequest->psid }}</strong> &rarr; Verify Candidate Name &amp; Amount &rarr; Confirm Pay.</li>
                <li><strong>ATM / OTC / Retailers:</strong> Select 1Bill at any ATM, or provide the 24-digit PSID at any Bank Branch, Easypaisa, JazzCash, or TCS outlet.</li>
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

</body>
</html>

