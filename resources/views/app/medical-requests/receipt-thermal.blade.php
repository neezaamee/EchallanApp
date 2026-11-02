<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thermal Receipt - {{ $medicalRequest->psid }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @if(!empty($isPdf))
            @page {
                size: 80mm auto;
                margin: 4mm 6mm 4mm 6mm;
            }
            body {
                background: #ffffff;
                color: #000000;
                font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
                font-size: 10px;
                line-height: 1.35;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .receipt-container {
                width: 100%;
                margin: 0;
                padding: 0;
                background: #ffffff;
                box-shadow: none;
            }
        @else
            /* Screen Preview */
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                background-color: #f1f5f9;
                color: #000000;
                font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
                font-size: 10px;
                line-height: 1.35;
                margin: 0;
                padding: 0;
            }
            .receipt-container {
                width: 78mm;
                margin: 15px auto;
                background: #ffffff;
                padding: 4mm 6mm;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                box-sizing: border-box;
            }

            /* Browser Print (window.print()) */
            @media print {
                @page {
                    size: 80mm auto;
                    margin: 0 !important;
                }
                html, body {
                    width: 80mm !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #ffffff !important;
                }
                .no-print-toolbar {
                    display: none !important;
                }
                .receipt-container {
                    width: 80mm !important;
                    max-width: 80mm !important;
                    margin: 0 !important;
                    padding: 3mm 6mm !important;
                    box-shadow: none !important;
                    box-sizing: border-box !important;
                }
            }

            /* On-Screen Toolbar */
            .no-print-toolbar {
                background: #1e293b;
                color: #ffffff;
                padding: 10px 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 100;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            }

            .no-print-toolbar .btn {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 6px 14px;
                font-size: 12px;
                font-weight: 600;
                border-radius: 4px;
                border: none;
                cursor: pointer;
                text-decoration: none;
            }

            .btn-print {
                background: #2563eb;
                color: #ffffff;
            }
            .btn-print:hover {
                background: #1d4ed8;
            }

            .btn-download {
                background: #dc2626;
                color: #ffffff;
            }
            .btn-download:hover {
                background: #b91c1c;
            }

            .btn-close-view {
                background: #475569;
                color: #ffffff;
            }
            .btn-close-view:hover {
                background: #334155;
            }
        @endif

        /* Typography & Layout */
        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #000000;
        }

        .header h1 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .header p {
            font-size: 9px;
            color: #222222;
        }

        .title {
            text-align: center;
            font-size: 11.5px;
            font-weight: bold;
            margin: 5px 0;
            padding: 4px 0;
            border-top: 1px dashed #000000;
            border-bottom: 1px dashed #000000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .line {
            height: 0;
            border: none;
            border-top: 1px dashed #000000;
            margin: 5px 0;
        }

        .double-line {
            height: 0;
            border: none;
            border-top: 2px solid #000000;
            margin: 5px 0;
        }

        /* Tables for 100% Identical DomPDF & Browser Alignment */
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1px 0;
        }

        .receipt-table td {
            padding: 1.5px 0;
            font-size: 10.5px;
            vertical-align: top;
        }

        .receipt-table .lbl {
            font-weight: bold;
            text-align: left;
            width: 36%;
            white-space: nowrap;
        }

        .receipt-table .val {
            text-align: right;
            width: 64%;
            word-break: break-word;
        }

        .section-heading {
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            letter-spacing: 0.5px;
            margin: 3px 0 2px 0;
        }

        /* PSID Box */
        .psid-box {
            text-align: center;
            margin: 5px 0;
            padding: 5px 1px;
            border: 1.5px solid #000000;
            background: #fafafa;
        }

        .psid-title {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .psid-number {
            font-family: 'DejaVu Sans Mono', 'Courier New', Courier, monospace;
            font-size: 13.5px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Barcode Box */
        .barcode-box {
            text-align: center;
            margin: 4px 0;
            padding: 0;
        }

        .barcode-box svg {
            display: inline-block;
            max-width: 96%;
            height: 28px;
        }

        .barcode-text {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            font-size: 8px;
            letter-spacing: 1px;
            text-align: center;
            margin-top: 1px;
        }

        /* Amount Box */
        .amount-box {
            text-align: center;
            margin: 5px 0;
            padding: 5px;
            border: 1.5px solid #000000;
        }

        .amount-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .amount-value {
            font-size: 15px;
            font-weight: bold;
            margin-top: 1px;
        }

        /* Instructions */
        .instructions {
            font-size: 8.5px;
            line-height: 1.35;
            margin: 5px 0;
            padding: 4px 5px;
            border: 1px dashed #000000;
        }

        .instructions strong {
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1.5px solid #000000;
            font-size: 8px;
            line-height: 1.35;
        }
    </style>
</head>
<body>

@if(empty($isPdf))
<!-- On-Screen Action Toolbar (Never rendered in PDF) -->
<div class="no-print-toolbar">
    <div style="display: flex; align-items: center; gap: 8px;">
        <span style="font-weight: bold; font-size: 13px;">80mm Thermal Receipt</span>
        <span style="background: rgba(255,255,255,0.2); padding: 2px 7px; border-radius: 3px; font-size: 11px;">
            {{ $medicalRequest->isPaid() ? 'PAID' : 'UNPAID' }}
        </span>
    </div>
    <div style="display: flex; gap: 6px;">
        <button onclick="window.print()" class="btn btn-print">
            Print Slip
        </button>
        <a href="{{ route('medical-requests.thermal.download', $medicalRequest->id) }}" class="btn btn-download">
            Download PDF
        </a>
        <a href="{{ route('medical-requests.show', $medicalRequest->id) }}" class="btn btn-close-view">
            Back
        </a>
    </div>
</div>
@endif

<div class="receipt-container">
    <!-- Header -->
    <div class="header">
        <h1>City Traffic Police <br>Faisalabad</h1>
    </div>

    <!-- Title -->
    @if($medicalRequest->isPaid())
        <div class="title">PAYMENT RECEIPT</div>
    @else
        <div class="title">1BILL INVOICE / MEDICAL SLIP</div>
    @endif

    <!-- Request Metadata Table -->
    <table class="receipt-table">
        <tr>
            <td class="lbl">Date &amp; Time:</td>
            <td class="val">{{ $medicalRequest->created_at?->format('d-M-Y h:i A') }}</td>
        </tr>
        <tr>
            <td class="lbl">Center:</td>
            <td class="val">{{ $medicalRequest->medicalCenter->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="lbl">Token / Req #:</td>
            <td class="val bold">{{ $medicalRequest->id }}</td>
        </tr>        
        <tr>
            <td class="lbl">Payment Status:</td>
            <td class="val bold">{{ strtoupper($medicalRequest->payment_status) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- Citizen Information -->
    <div class="section-heading">CANDIDATE INFORMATION</div>
    <div class="line"></div>

    <table class="receipt-table">
        <tr>
            <td class="lbl">Name:</td>
            <td class="val bold">{{ $medicalRequest->citizen->full_name ?? 'N/A' }}</td>
        </tr>
        {{-- <tr>
            <td class="lbl">Father Name:</td>
            <td class="val">{{ $medicalRequest->citizen->father_name ?? 'N/A' }}</td>
        </tr> --}}
        <tr>
            <td class="lbl">CNIC:</td>
            <td class="val bold" style="font-family: 'DejaVu Sans Mono', monospace;">{{ $medicalRequest->citizen->cnic ?? 'N/A' }}</td>
        </tr>
        {{-- <tr>
            <td class="lbl">Mobile:</td>
            <td class="val">{{ $medicalRequest->citizen->phone ?? 'N/A' }}</td>
        </tr> --}}
        
    </table>

    <div class="line"></div>

    <!-- 1Bill Consumer Number (PSID) & Barcode -->
    <div class="psid-box">
        <div class="psid-title">1Bill Consumer Number (PSID)</div>
        <div class="psid-number">{{ chunk_split($medicalRequest->psid, 4, ' ') }}</div>
    </div>
{{--
    @if(!empty($barcodeSvg))
        <div class="barcode-box">
            {!! $barcodeSvg !!}
            <div class="barcode-text">*{{ $medicalRequest->psid }}*</div>
        </div>
    @endif
--}}
    <!-- Amount Box -->
    <div class="amount-box">
        <div class="amount-label">{{ $medicalRequest->isPaid() ? 'AMOUNT PAID' : 'AMOUNT PAYABLE' }}</div>
        <div class="amount-value">PKR {{ number_format($medicalRequest->amount, 2) }}</div>
        {{--<div style="font-size: 8px; color: #222222; margin-top: 1px;">Medical Fitness Examination Fee</div>--}}
    </div>

    <!-- If Paid: Transaction and Doctor Details -->
    @if($medicalRequest->isPaid())
        @php $payment = $medicalRequest->latestPayment; @endphp
        <div class="line"></div>
        <div class="section-heading">PAYMENT DETAILS</div>
        <div class="line"></div>

        <table class="receipt-table">
            @if($payment)
                <tr>
                    <td class="lbl">Txn ID:</td>
                    <td class="val bold" style="font-family: 'DejaVu Sans Mono', monospace;">{{ $payment->transaction_id }}</td>
                </tr>
                <tr>
                    <td class="lbl">Method:</td>
                    <td class="val">{{ strtoupper(str_replace('_', ' ', $payment->payment_method ?? '1Bill')) }}</td>
                </tr>
                <tr>
                    <td class="lbl">Paid At:</td>
                    <td class="val">{{ $payment->paid_at?->format('d-M-Y h:i A') }}</td>
                </tr>
            @else
                <tr>
                    <td class="lbl">Status:</td>
                    <td class="val bold">VERIFIED IN PORTAL</td>
                </tr>
            @endif
        </table>

        <div class="line"></div>
        <div class="section-heading">FITNESS EXAM RESULT</div>
        <div class="line"></div>

        <table class="receipt-table">
            <tr>
                <td class="lbl">Exam Status:</td>
                <td class="val bold" style="font-size: 11px;">
                    @if($medicalRequest->status === 'passed')
                        PASSED (FIT)
                    @elseif($medicalRequest->status === 'failed')
                        FAILED (UNFIT)
                    @else
                        PENDING EXAM
                    @endif
                </td>
            </tr>
            @if($medicalRequest->doctorActionBy)
                <tr>
                    <td class="lbl">Doctor:</td>
                    <td class="val">{{ $medicalRequest->doctorActionBy->name }}</td>
                </tr>
                @if($medicalRequest->doctor_action_at)
                    <tr>
                        <td class="lbl">Action Date:</td>
                        <td class="val">{{ $medicalRequest->doctor_action_at->format('d-M-Y h:i A') }}</td>
                    </tr>
                @endif
            @endif
        </table>
    @else
        <!-- Unpaid Instructions -->
        <div class="instructions">
            <strong>HOW TO PAY VIA 1LINK 1BILL:</strong><br>
            1. Open any Bank App / ATM / Easypaisa / JazzCash<br>
            2. Select '1Bill' &rarr; '1Bill Invoices / Vouchers'<br>
            3. Enter 24-digit PSID: <strong>{{ $medicalRequest->psid }}</strong><br>
            4. Confirm Candidate Name &amp; Pay Rs. {{ number_format($medicalRequest->amount, 0) }}.<br>
            * Present this verified slip for doctor examination.
        </div>
    @endif

    <div class="double-line"></div>

    <!-- Footer -->
    <div class="footer">
        <p class="bold">Computer Generated Thermal Slip</p>
        <p>City Traffic Police Punjab</p>
        <p>Printed: {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</div>

@if(empty($isPdf))
<script>
    window.addEventListener('load', function() {
        @if(request('print', 1))
            setTimeout(function() {
                window.print();
            }, 300);
        @endif
    });
</script>
@endif

</body>
</html>
