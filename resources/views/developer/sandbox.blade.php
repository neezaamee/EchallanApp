@extends('layouts.app')

@section('cms-main-content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Bank API Sandbox (Developer Tool)</h5>
            <p class="mb-0 text-muted small">Simulate payment callbacks from the Bank API.</p>
        </div>
        <div class="card-body bg-light">
            <div class="row g-3">
                <div class="col-12">
                    @php
                        $debugChallans = \App\Models\Challan::where('payment_status', '!=', 'paid')->latest()->take(5)->get();
                        dump('Debug Count: ' . $debugChallans->count());
                    @endphp
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-none border">
                        <div class="card-body">
                            <h6>Medical Requests (Unpaid)</h6>
                            @php
                                $medicalRequests = \App\Models\MedicalRequest::where('payment_status', '!=', 'paid')->latest()->take(5)->get();
                            @endphp
                            <ul class="list-group list-group-flush">
                                @forelse($medicalRequests as $req)
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                        <div>
                                            <span class="d-block fw-bold text-primary">{{ $req->psid }}</span>
                                            <span class="small text-muted">Medical - {{ $req->citizen ? $req->citizen->full_name : 'N/A' }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-success pay-btn" data-psid="{{ $req->psid }}">
                                            Pay
                                        </button>
                                    </li>
                                @empty
                                    <li class="list-group-item bg-transparent text-muted small">No unpaid requests.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-none border">
                        <div class="card-body">
                            <h6>Challans (Unpaid)</h6>
                            @php
                                $challans = \App\Models\Challan::where('payment_status', '!=', 'paid')->latest()->take(5)->get();
                            @endphp
                            <ul class="list-group list-group-flush">
                                @forelse($challans as $challan)
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                        <div>
                                            <span class="d-block fw-bold text-danger">{{ $challan->psid }}</span>
                                            <span class="small text-muted">
                                                {{ $challan->violation_name }} 
                                                ({{ $challan->vehicle_type === 'motorcycle' ? 'Bike' : 'Car' }})
                                            </span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-success pay-btn" data-psid="{{ $challan->psid }}">
                                            Pay
                                        </button>
                                    </li>
                                @empty
                                    <li class="list-group-item bg-transparent text-muted small">No unpaid challans.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-none border">
                        <div class="card-body">
                            <h6>Generic PSID Test</h6>
                            <div class="input-group mb-3">
                                <input type="text" id="manual-psid" class="form-control" placeholder="Enter any PSID">
                                <button class="btn btn-outline-primary" type="button" id="manual-pay-btn">Pay</button>
                            </div>
                            <div id="status-message" class="mt-2 text-center small"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Modal -->
<div class="modal fade" id="sandboxPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Simulate Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="sandbox-payment-form">
                    <div class="mb-3">
                        <label class="form-label">PSID</label>
                        <input type="text" id="modal-psid" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select id="modal-payment-method" class="form-select">
                            <option value="1bill_invoice">1Bill Invoice (Bank)</option>
                            <option value="atm">ATM</option>
                            <option value="mobile_banking">Mobile Banking App</option>
                            <option value="easypaisa">EasyPaisa / JazzCash</option>
                            <option value="cash">Over the Counter (Cash)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirm-pay-btn">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButtons = document.querySelectorAll('.pay-btn');
        const manualPayBtn = document.getElementById('manual-pay-btn');
        const confirmPayBtn = document.getElementById('confirm-pay-btn');
        
        let paymentModal = new bootstrap.Modal(document.getElementById('sandboxPaymentModal'));
        let selectedPsid = null;

        function openPaymentModal(psid) {
            if(!psid) return;
            selectedPsid = psid;
            document.getElementById('modal-psid').value = psid;
            paymentModal.show();
        }

        payButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                openPaymentModal(this.getAttribute('data-psid'));
            });
        });

        if(manualPayBtn){
            manualPayBtn.addEventListener('click', function() {
                openPaymentModal(document.getElementById('manual-psid').value);
            });
        }

        confirmPayBtn.addEventListener('click', function() {
            if(!selectedPsid) return;
            
            const method = document.getElementById('modal-payment-method').value;
            simulatePayment(selectedPsid, method);
            paymentModal.hide();
        });

        function simulatePayment(psid, method) {
            // 1. Call Mock Bank API to set status to PAID
            fetch('/api/mock-bank/pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    psid: psid,
                    payment_method: method
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Mock Bank: ', data);
                
                // 2. Call our App's Internal Sync Endpoint
                // We pass the transaction ID from mock bank if available, or let sync handle it
                return fetch('/api/mock-bank/sync-local-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        psid: psid,
                        payment_method: method
                    })
                });
            })
            .then(response => response.json())
            .then(syncData => {
                console.log('Sync Status: ', syncData);
                alert('Payment Simulated via ' + method + '!\nPSID: ' + psid + '\n' + syncData.message);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to simulate payment or sync status.');
            });
        }
    });
</script>
@endsection
