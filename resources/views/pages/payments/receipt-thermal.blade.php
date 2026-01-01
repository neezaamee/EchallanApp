<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thermal Receipt - {{ $payment->transaction_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.4;
            color: #000;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 9px;
        }

        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
            padding: 5px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }

        .double-line {
            border-bottom: 2px solid #000;
            margin: 8px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .label {
            font-weight: bold;
        }

        .value {
            text-align: right;
        }

        .amount-box {
            text-align: center;
            margin: 10px 0;
            padding: 8px;
            border: 2px solid #000;
        }

        .amount-label {
            font-size: 9px;
        }

        .amount-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 3px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 5px;
            border-top: 2px solid #000;
            font-size: 8px;
        }

        .footer p {
            margin: 2px 0;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        @media print {
            body {
                width: 80mm;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>E-CHALLAN SYSTEM</h1>
        <p>Payment Receipt</p>
    </div>

    <!-- Title -->
    <div class="title">PAYMENT RECEIPT</div>

    <!-- Receipt Info -->
    <div class="row">
        <span class="label">Receipt:</span>
        <span class="value">RCP-{{ date('Ymd') }}-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="row">
        <span class="label">Date:</span>
        <span class="value">{{ $payment->paid_at->format('d-M-Y h:i A') }}</span>
    </div>

    <div class="line"></div>

    <!-- Payment Details -->
    <div class="center bold">PAYMENT DETAILS</div>
    <div class="line"></div>

    <div class="row">
        <span class="label">PSID:</span>
        <span class="value">{{ $payment->psid }}</span>
    </div>
    <div class="row">
        <span class="label">TXN ID:</span>
        <span class="value">{{ $payment->transaction_id }}</span>
    </div>
    <div class="row">
        <span class="label">Method:</span>
        <span class="value">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</span>
    </div>
    <div class="row">
        <span class="label">Status:</span>
        <span class="value bold">{{ strtoupper($payment->status) }}</span>
    </div>

    <!-- Amount -->
    <div class="amount-box">
        <div class="amount-label">AMOUNT PAID</div>
        <div class="amount-value">PKR {{ number_format($payment->amount, 2) }}</div>
    </div>

    <div class="line"></div>

    <!-- Citizen Info -->
    <div class="center bold">CITIZEN INFORMATION</div>
    <div class="line"></div>

    <div class="row">
        <span class="label">Name:</span>
        <span class="value">{{ $payment->medicalRequest->citizen->full_name }}</span>
    </div>

    <div class="row">
        <span class="label">CNIC:</span>
        <span class="value">{{ $payment->medicalRequest->citizen->cnic }}</span>
    </div>

    <div class="line"></div>

    <!-- Medical Center -->
    <div class="center bold">MEDICAL CENTER</div>
    <div class="line"></div>

    <div class="row">
        <span class="label">Center:</span>
        <span class="value">{{ $payment->medicalRequest->medicalCenter->name }}</span>
    </div>

    @if ($payment->medicalRequest->doctorActionBy)
        <div class="row">
            <span class="label">Doctor:</span>
            <span class="value">{{ $payment->medicalRequest->doctorActionBy->name }}</span>
        </div>
        @if ($payment->medicalRequest->doctor_action_at)
            <div class="row">
                <span class="label">Time:</span>
                <span class="value">{{ $payment->medicalRequest->doctor_action_at->format('d-M-Y') }}</span>
            </div>
        @endif
        {{-- Pass/Fail Status --}}
        @if ($payment->medicalRequest->status)
            <div class="row">
                <span class="label">Result:</span>
                <span class="value bold"
                    style="font-size: 14px; {{ $payment->medicalRequest->status == 'passed' ? 'text-transform:uppercase;' : '' }}">
                    {{ strtoupper($payment->medicalRequest->status) }}
                </span>
            </div>
        @endif
    @endif

    <div class="double-line"></div>

    <!-- Footer -->
    <div class="footer">
        <p class="bold">Computer Generated Receipt</p>
        <p>No signature required</p>
        <p>Thank you for your payment!</p>
        <div class="line"></div>
        <p>{{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>

</html>
