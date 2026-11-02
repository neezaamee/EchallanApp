@extends('layouts.app')
@section('page-title', 'Medical Request - ' . $medicalRequest->psid)

@section('cms-main-content')
<div class="card mb-3">
    <!-- Header -->
    <div class="card-header bg-light py-2">
        <div class="row flex-between-center g-2">
            <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center">
                    <span class="fas fa-file-medical text-primary fs-3 me-2"></span>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h5 class="mb-0 text-900">Medical Request #{{ $medicalRequest->id }}</h5>
                            <span class="badge badge-soft-primary font-monospace fs--1 user-select-all px-2 py-1" id="psidBadge">{{ $medicalRequest->psid }}</span>
                            <button type="button" class="btn btn-link p-0 text-primary fs--1" onclick="copyPsid()" title="Copy PSID">
                                <i class="fas fa-copy" id="copyIcon"></i>
                            </button>
                        </div>
                        <small class="text-500 fs--2">Created: {{ $medicalRequest->created_at?->format('d/m/Y h:i A') }} ({{ $medicalRequest->created_at?->diffForHumans() }})</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-auto ms-auto text-end">
                <div class="d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
                    <a href="{{ route('medical-requests.index') }}" class="btn btn-falcon-default btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>

                    <!-- PRIMARY OPTIMIZED ACTION: Direct 80mm Thermal Receipt Print -->
                    <a href="{{ route('medical-requests.thermal', $medicalRequest->id) }}?print=1" target="_blank" class="btn btn-primary btn-sm shadow-none" title="Direct Thermal Print (80mm)">
                        <i class="fas fa-print me-1"></i> Print Thermal Receipt
                    </a>

                    @if($medicalRequest->payment_status === 'unpaid')
                        @can('payments:process')
                            <a href="{{ route('payments.create', $medicalRequest->psid) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-credit-card me-1"></i> Pay Fee
                            </a>
                        @endcan
                    @endif

                    <!-- OTHER OPTIONS (Not in optimized form, neatly grouped) -->
                    <div class="dropdown font-sans-serif">
                        <button class="btn btn-falcon-default btn-sm dropdown-toggle" type="button" id="moreOptionsDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Other Formats &amp; Options">
                            <i class="fas fa-ellipsis-v me-1"></i> Other Options
                        </button>
                        <div class="dropdown-menu dropdown-menu-end border py-2 shadow-sm" aria-labelledby="moreOptionsDropdown">
                            <h6 class="dropdown-header fs--2 text-uppercase text-500 py-1">Receipt &amp; Slip Formats</h6>
                            <a class="dropdown-item fs--1 py-1" href="{{ route('medical-requests.thermal.download', $medicalRequest->id) }}">
                                <i class="fas fa-receipt text-primary me-2"></i> Download Thermal PDF (80mm)
                            </a>
                            <a class="dropdown-item fs--1 py-1" href="{{ route('medical-requests.voucher', $medicalRequest->id) }}" target="_blank">
                                <i class="fas fa-file-alt text-secondary me-2"></i> View A4 Challan (2-Copy Slip)
                            </a>
                            <a class="dropdown-item fs--1 py-1" href="{{ route('medical-requests.voucher.download', $medicalRequest->id) }}">
                                <i class="fas fa-file-pdf text-danger me-2"></i> Download A4 Voucher (PDF)
                            </a>

                            @if($medicalRequest->payment_status === 'paid' && $medicalRequest->latestPayment)
                                <div class="dropdown-divider my-1"></div>
                                <h6 class="dropdown-header fs--2 text-uppercase text-500 py-1">Paid Invoices</h6>
                                <a class="dropdown-item fs--1 py-1" href="{{ route('payments.receipt.download', $medicalRequest->latestPayment->id) }}">
                                    <i class="fas fa-file-invoice text-success me-2"></i> Standard A4 PDF Receipt
                                </a>
                                <a class="dropdown-item fs--1 py-1" href="{{ route('payments.receipt', $medicalRequest->latestPayment->id) }}" target="_blank">
                                    <i class="fas fa-external-link-alt text-info me-2"></i> View HTML Receipt
                                </a>
                                <a class="dropdown-item fs--1 py-1" href="{{ route('payments.success', $medicalRequest->latestPayment->id) }}">
                                    <i class="fas fa-check-circle text-success me-2"></i> Payment Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Ribbon -->
    <div class="card-body bg-light border-top border-bottom py-2">
        <div class="row align-items-center g-2">
            <div class="col-sm-6 d-flex align-items-center gap-2">
                <span class="text-700 fs--1 fw-semi-bold">Payment Status:</span>
                @if($medicalRequest->payment_status === 'paid')
                    <span class="badge badge-soft-success fs--1 px-2 py-1">
                        <i class="fas fa-check-circle me-1"></i> PAID
                    </span>
                    @if($medicalRequest->latestPayment)
                        <span class="text-muted fs--2 d-none d-md-inline">&bull; {{ strtoupper($medicalRequest->latestPayment->payment_method ?? '1Bill') }} on {{ $medicalRequest->latestPayment->paid_at?->format('d/m/Y h:i A') }}</span>
                    @endif
                @else
                    <span class="badge badge-soft-danger fs--1 px-2 py-1">
                        <i class="fas fa-clock me-1"></i> UNPAID
                    </span>
                    <span class="text-muted fs--2">&bull; Fee: Rs. {{ number_format($medicalRequest->amount, 2) }}</span>
                @endif
            </div>
            <div class="col-sm-6 text-sm-end d-flex align-items-center justify-content-sm-end gap-2">
                <span class="text-700 fs--1 fw-semi-bold">Fitness Exam:</span>
                @if($medicalRequest->status === 'passed')
                    <span class="badge badge-soft-success fs--1 px-2 py-1">
                        <i class="fas fa-check-double me-1"></i> PASSED (FIT)
                    </span>
                @elseif($medicalRequest->status === 'failed')
                    <span class="badge badge-soft-danger fs--1 px-2 py-1">
                        <i class="fas fa-times-circle me-1"></i> FAILED (UNFIT)
                    </span>
                @else
                    <span class="badge badge-soft-warning fs--1 px-2 py-1">
                        <i class="fas fa-hourglass-half me-1"></i> PENDING EXAM
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card-body p-3">
        <div class="row g-3">
            <!-- Left Column: Primary Information -->
            <div class="col-lg-8">
                <!-- Citizen / Candidate Information -->
                <div class="card border mb-3">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-900 fs--1">
                            <i class="fas fa-user-circle text-primary me-2"></i>Candidate Information
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Full Name</div>
                                <div class="fw-bold text-900 fs-0">{{ $medicalRequest->citizen->full_name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Father / Husband Name</div>
                                <div class="fw-semi-bold text-800">{{ $medicalRequest->citizen->father_name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">CNIC Number</div>
                                <div class="font-monospace fw-bold text-primary fs-0">{{ $medicalRequest->citizen->cnic ?? 'N/A' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Mobile / Phone</div>
                                <div class="fw-semi-bold text-800">
                                    <i class="fas fa-phone-alt text-400 me-1"></i>{{ $medicalRequest->citizen->phone ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Gender</div>
                                <div class="fw-semi-bold text-800">{{ ucfirst($medicalRequest->citizen->gender ?? 'N/A') }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Registration Type</div>
                                <div>
                                    @if($medicalRequest->citizen?->user)
                                        <span class="badge badge-soft-success fs--2"><i class="fas fa-check-circle me-1"></i>Registered Citizen Account ({{ $medicalRequest->citizen->user->email }})</span>
                                    @else
                                        <span class="badge badge-soft-secondary fs--2"><i class="fas fa-user-tag me-1"></i>Walk-in Center Registration</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 1Bill PSID & Payment Information -->
                <div class="card border mb-3">
                    <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-900 fs--1">
                            <i class="fas fa-barcode text-primary me-2"></i>1Bill Consumer ID &amp; Payment Details
                        </h6>
                        <span class="badge bg-primary text-white font-monospace fs--2">1Link 1Bill</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row align-items-center mb-3 g-2">
                            <div class="col-md-7">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">1Bill Consumer Number (PSID)</div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fw-bold text-900 font-monospace text-primary fs-6 text-break">{{ chunk_split($medicalRequest->psid, 4, ' ') }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="copyPsid()">
                                        <i class="fas fa-copy me-1"></i>Copy
                                    </button>
                                </div>
                                <div class="text-500 fs--2 mt-1">24-digit global 1Bill ID &bull; Valid across all Pakistani banking channels</div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Medical Exam Fee</div>
                                <div class="fs-6 fw-bold text-success">Rs. {{ number_format($medicalRequest->amount, 2) }}</div>
                                <span class="badge badge-soft-info fs--2">Govt. Medical Examination Account</span>
                            </div>
                        </div>

                        <!-- Barcode Rendering -->
                        <div class="p-2 bg-light rounded text-center mb-3 overflow-hidden">
                            <div class="d-flex justify-content-center">
                                {!! $barcodeSvg !!}
                            </div>
                            <div class="font-monospace text-500 fs--2 mt-1">*{{ $medicalRequest->psid }}*</div>
                        </div>

                        <!-- Payment Instructions or Paid Summary -->
                        @if($medicalRequest->payment_status === 'unpaid')
                            <div class="alert alert-soft-primary border-2 mb-0 p-3" role="alert">
                                <h6 class="alert-heading text-primary fs--1 fw-bold mb-2">
                                    <i class="fas fa-info-circle me-1"></i> How to Pay via 1Link 1Bill:
                                </h6>
                                <ol class="mb-0 ps-3 fs--1 text-800">
                                    <li class="mb-1"><strong>Mobile Banking / Internet Banking:</strong> Open any bank app (HBL, Meezan, MCB, Allied, JazzCash, Easypaisa, Nayapay, SadaPay, etc.) &rarr; Go to <strong>Bill Payment</strong> &rarr; Select <strong>1Bill (Invoices / Vouchers)</strong> &rarr; Enter <strong>{{ $medicalRequest->psid }}</strong> &rarr; Verify Candidate Name &amp; Pay.</li>
                                    <li class="mb-1"><strong>ATM:</strong> Select Bill Payment &rarr; 1Bill &rarr; Enter 24-digit PSID &rarr; Confirm.</li>
                                    <li><strong>OTC / Cash Counter:</strong> Provide this 24-digit PSID to any 1Link bank branch cashier or retail shop.</li>
                                </ol>
                            </div>
                        @else
                            <div class="alert alert-soft-success border-2 mb-0 p-3" role="alert">
                                <h6 class="alert-heading text-success fs--1 fw-bold mb-2">
                                    <i class="fas fa-check-circle me-1"></i> Payment Verified
                                </h6>
                                @if($medicalRequest->latestPayment)
                                    <div class="row g-2 fs--1 text-800">
                                        <div class="col-sm-6"><strong>Transaction ID:</strong> <span class="font-monospace">{{ $medicalRequest->latestPayment->transaction_id }}</span></div>
                                        <div class="col-sm-6"><strong>Payment Method:</strong> {{ strtoupper($medicalRequest->latestPayment->payment_method ?? '1Link 1Bill') }}</div>
                                        <div class="col-sm-6"><strong>Paid At:</strong> {{ $medicalRequest->latestPayment->paid_at?->format('d/m/Y h:i A') }}</div>
                                        <div class="col-sm-6"><strong>Amount Paid:</strong> Rs. {{ number_format($medicalRequest->latestPayment->amount, 2) }}</div>
                                    </div>
                                @else
                                    <p class="mb-0 fs--1 text-800">Fee verified in portal. The candidate can now undergo doctor medical examination.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Medical Examination Details -->
                <div class="card border mb-3">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-900 fs--1">
                            <i class="fas fa-stethoscope text-primary me-2"></i>Medical Center &amp; Examination Status
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Assigned Medical Center</div>
                                <div class="fw-bold text-900 fs-0">{{ $medicalRequest->medicalCenter->name ?? 'N/A' }}</div>
                                <div class="text-500 fs--2">
                                    <i class="fas fa-map-marker-alt text-400 me-1"></i>
                                    {{ $medicalRequest->medicalCenter?->circle?->name ?? 'Circle' }}, {{ $medicalRequest->medicalCenter?->circle?->city?->name ?? 'City' }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Doctor Fitness Result</div>
                                <div>
                                    @if($medicalRequest->status === 'passed')
                                        <span class="badge badge-soft-success fs--1 px-2 py-1">
                                            <i class="fas fa-check-circle me-1"></i> PASSED (FIT FOR DRIVING)
                                        </span>
                                    @elseif($medicalRequest->status === 'failed')
                                        <span class="badge badge-soft-danger fs--1 px-2 py-1">
                                            <i class="fas fa-times-circle me-1"></i> FAILED (UNFIT)
                                        </span>
                                    @else
                                        <span class="badge badge-soft-warning fs--1 px-2 py-1">
                                            <i class="fas fa-hourglass-half me-1"></i> AWAITING EXAMINATION
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Medical Officer / Doctor</div>
                                <div class="fw-semi-bold text-800">
                                    @if($medicalRequest->doctorActionBy)
                                        <i class="fas fa-user-md text-primary me-1"></i>
                                        {{ $medicalRequest->doctorActionBy->name }}
                                        @if($medicalRequest->doctorActionBy->staff?->rank)
                                            <span class="text-500 fs--2">({{ $medicalRequest->doctorActionBy->staff->rank->name }})</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Awaiting Doctor Examination</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-1">Examination Timestamp</div>
                                <div class="fw-semi-bold text-800">
                                    @if($medicalRequest->doctor_action_at)
                                        {{ $medicalRequest->doctor_action_at->format('d/m/Y h:i A') }}
                                        <small class="text-500">({{ $medicalRequest->doctor_action_at->diffForHumans() }})</small>
                                    @else
                                        <span class="text-muted">&mdash;</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Actions (Doctor Role Only) -->
                        @role('doctor')
                            @if($medicalRequest->payment_status === 'paid' && $medicalRequest->status === 'pending')
                                <hr class="my-3">
                                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between p-3 bg-light rounded gap-2">
                                    <span class="fw-semi-bold text-900 fs--1">Action Candidate Examination:</span>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('medical-requests.update', $medicalRequest->id) }}" method="POST" onsubmit="return confirm('Confirm candidate is PASSED (Fit for Driving)?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="passed">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check-circle me-1"></i> Pass Examination
                                            </button>
                                        </form>
                                        <form action="{{ route('medical-requests.update', $medicalRequest->id) }}" method="POST" onsubmit="return confirm('Confirm candidate is FAILED (Unfit)?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="failed">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times-circle me-1"></i> Fail Examination
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif($medicalRequest->payment_status !== 'paid')
                                <div class="alert alert-soft-warning border-0 mb-0 mt-3 p-2 fs--2">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Doctor action is locked until the 1Bill fee (Rs. 200) is paid.
                                </div>
                            @endif
                        @endrole
                    </div>
                </div>
            </div>

            <!-- Right Column: Quick Actions & Audit Trail -->
            <div class="col-lg-4">
                <!-- Quick Actions Card -->
                <div class="card border mb-3">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-900 fs--1">
                            <i class="fas fa-bolt text-warning me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <!-- PRIMARY ACTION: One-Click Thermal Receipt Print -->
                        <a href="{{ route('medical-requests.thermal', $medicalRequest->id) }}?print=1" target="_blank" class="btn btn-primary btn-sm text-start py-2 w-100 mb-2 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-print fa-lg me-3"></i>
                                <div>
                                    <div class="fw-bold">Print Thermal Receipt</div>
                                    <small class="text-white-50 d-block fs--2">Direct 80mm counter slip</small>
                                </div>
                            </div>
                        </a>

                        @if($medicalRequest->payment_status === 'unpaid')
                            @can('payments:process')
                                <a href="{{ route('payments.create', $medicalRequest->psid) }}" class="btn btn-success btn-sm text-start py-2 w-100 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-credit-card fa-lg me-3"></i>
                                        <div>
                                            <div class="fw-bold">Process Payment</div>
                                            <small class="text-white-50 d-block fs--2">Direct cash / card payment</small>
                                        </div>
                                    </div>
                                </a>
                            @endcan
                        @endif

                        <!-- Secondary / Other Document Options (Available, but not in optimized form) -->
                        <div class="border-top pt-2 mt-2">
                            <div class="text-500 fs--2 text-uppercase fw-semi-bold mb-2">Other Document Options</div>
                            <div class="d-grid gap-1">
                                <a href="{{ route('medical-requests.thermal.download', $medicalRequest->id) }}" class="btn btn-falcon-default btn-sm text-start py-1">
                                    <i class="fas fa-receipt me-2 text-primary"></i> Download Thermal PDF
                                </a>
                                <a href="{{ route('medical-requests.voucher', $medicalRequest->id) }}" target="_blank" class="btn btn-falcon-default btn-sm text-start py-1">
                                    <i class="fas fa-file-alt me-2 text-secondary"></i> View A4 Challan (2-Copy)
                                </a>
                                <a href="{{ route('medical-requests.voucher.download', $medicalRequest->id) }}" class="btn btn-falcon-default btn-sm text-start py-1">
                                    <i class="fas fa-file-pdf me-2 text-danger"></i> Download A4 Voucher PDF
                                </a>
                                @if($medicalRequest->payment_status === 'paid' && $medicalRequest->latestPayment)
                                    <a href="{{ route('payments.receipt.download', $medicalRequest->latestPayment->id) }}" class="btn btn-falcon-default btn-sm text-start py-1">
                                        <i class="fas fa-file-invoice me-2 text-success"></i> Download Standard PDF Receipt
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audit Trail Card -->
                <div class="card border">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-900 fs--1">
                            <i class="fas fa-history text-secondary me-2"></i>Audit Trail &amp; Log
                        </h6>
                    </div>
                    <div class="card-body p-3 fs--1">
                        <div class="mb-3">
                            <div class="text-500 fs--2 text-uppercase fw-semi-bold">Generated Date</div>
                            <div class="fw-semi-bold text-900">{{ $medicalRequest->created_at?->format('d/m/Y h:i A') }}</div>
                            <small class="text-muted">({{ $medicalRequest->created_at?->diffForHumans() }})</small>
                        </div>

                        <div class="mb-3">
                            <div class="text-500 fs--2 text-uppercase fw-semi-bold">Generated By</div>
                            @if($medicalRequest->creator)
                                <div class="fw-semi-bold text-900">{{ $medicalRequest->creator->name }}</div>
                                <div class="text-500 fs--2">{{ $medicalRequest->creator->email }}</div>
                                @if($medicalRequest->creator->roles->isNotEmpty())
                                    <span class="badge badge-soft-primary fs--2 mt-1">{{ ucwords(str_replace('_', ' ', $medicalRequest->creator->roles->first()->name)) }}</span>
                                @endif
                            @else
                                <span class="badge badge-soft-secondary fs--2"><i class="fas fa-user me-1"></i>Citizen Self-Registration</span>
                            @endif
                        </div>

                        @if($medicalRequest->latestPayment)
                            <div class="mb-3">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold">Payment Verified At</div>
                                <div class="fw-semi-bold text-success">{{ $medicalRequest->latestPayment->paid_at?->format('d/m/Y h:i A') }}</div>
                                <small class="text-muted">({{ $medicalRequest->latestPayment->paid_at?->diffForHumans() }})</small>
                            </div>
                        @endif

                        @if($medicalRequest->doctor_action_at)
                            <div class="mb-3">
                                <div class="text-500 fs--2 text-uppercase fw-semi-bold">Doctor Examined At</div>
                                <div class="fw-semi-bold text-primary">{{ $medicalRequest->doctor_action_at->format('d/m/Y h:i A') }}</div>
                                <small class="text-muted">({{ $medicalRequest->doctor_action_at->diffForHumans() }})</small>
                            </div>
                        @endif

                        <div class="border-top pt-2 mt-2 text-500 fs--2">
                            <span>System ID: #{{ $medicalRequest->id }} &bull; Updated: {{ $medicalRequest->updated_at?->format('d/m/Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyPsid() {
    const psid = '{{ $medicalRequest->psid }}';
    navigator.clipboard.writeText(psid).then(() => {
        const icon = document.getElementById('copyIcon');
        if (icon) {
            icon.className = 'fas fa-check text-success';
            setTimeout(() => { icon.className = 'fas fa-copy'; }, 2000);
        }
    });
}
</script>
@endsection
