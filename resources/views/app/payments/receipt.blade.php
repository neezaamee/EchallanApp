<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->transaction_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .receipt-title {
            text-align: center;
            background-color: #27ae60;
            color: white;
            padding: 15px;
            margin-bottom: 30px;
            font-size: 20px;
            font-weight: bold;
        }

        .receipt-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .receipt-info-row {
            display: table-row;
        }

        .receipt-info-label {
            display: table-cell;
            width: 40%;
            padding: 8px 0;
            font-weight: bold;
            color: #555;
        }

        .receipt-info-value {
            display: table-cell;
            padding: 8px 0;
            color: #333;
        }

        .section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .amount-section {
            text-align: center;
            background-color: #27ae60;
            color: white;
            padding: 20px;
            margin: 30px 0;
            border-radius: 5px;
        }

        .amount-label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 32px;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #27ae60;
            color: white;
            border-radius: 3px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            color: #7f8c8d;
            font-size: 11px;
        }

        .footer p {
            margin: 5px 0;
        }

        .divider {
            border-bottom: 1px dashed #bdc3c7;
            margin: 20px 0;
        }

        @media print {
            .receipt-container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1>E-CHALLAN PAYMENT SYSTEM</h1>
            <p>Medical Request Payment Receipt</p>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">
            PAYMENT RECEIPT
        </div>

        <!-- Receipt Metadata -->
        <div class="section">
            <div class="receipt-info">
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Receipt Number:</div>
                    <div class="receipt-info-value">
                        RCP-{{ date('Ymd') }}-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Receipt Date:</div>
                    <div class="receipt-info-value">{{ $payment->paid_at->format('F d, Y') }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Receipt Time:</div>
                    <div class="receipt-info-value">{{ $payment->paid_at->format('h:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="section">
            <div class="section-title">Payment Details</div>
            <div class="receipt-info">
                <div class="receipt-info-row">
                    <div class="receipt-info-label">PSID:</div>
                    <div class="receipt-info-value">{{ $payment->psid }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Transaction ID:</div>
                    <div class="receipt-info-value">{{ $payment->transaction_id }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Payment Method:</div>
                    <div class="receipt-info-value">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Payment Status:</div>
                    <div class="receipt-info-value"><span class="status-badge">{{ strtoupper($payment->status) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">AMOUNT PAID</div>
            <div class="amount-value">PKR {{ number_format($payment->amount, 2) }}</div>
        </div>

        <!-- Citizen Information -->
        <div class="section">
            <div class="section-title">Citizen Information</div>
            <div class="receipt-info">
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Name:</div>
                    <div class="receipt-info-value">{{ $payment->medicalRequest->citizen->full_name }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">CNIC:</div>
                    <div class="receipt-info-value">{{ $payment->medicalRequest->citizen->cnic }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Contact:</div>
                    <div class="receipt-info-value">{{ $payment->medicalRequest->citizen->phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Medical Center Information -->
        <div class="section">
            <div class="section-title">Medical Center</div>
            <div class="receipt-info">
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Center Name:</div>
                    <div class="receipt-info-value">{{ $payment->medicalRequest->medicalCenter->name }}</div>
                </div>
                <div class="receipt-info-row">
                    <div class="receipt-info-label">Request Status:</div>
                    <div class="receipt-info-value">{{ ucfirst($payment->medicalRequest->status) }}</div>
                </div>
                @if ($payment->medicalRequest->doctorActionBy)
                    <div class="receipt-info-row">
                        <div class="receipt-info-label">Doctor:</div>
                        <div class="receipt-info-value">{{ $payment->medicalRequest->doctorActionBy->name }}</div>
                    </div>
                    @if ($payment->medicalRequest->doctor_action_at)
                        <div class="receipt-info-row">
                            <div class="receipt-info-label">Action Date:</div>
                            <div class="receipt-info-value">
                                {{ $payment->medicalRequest->doctor_action_at->format('F d, Y') }}</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is a computer-generated receipt and does not require a signature.</strong></p>
            <p>For any queries, please contact the E-Challan support team.</p>
            <p>Thank you for your payment!</p>
            <div class="divider"></div>
            <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>

</html>
