<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\MedicalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments (Admin/Accountant only)
     */
    public function index(Request $request)
    {
        $query = Payment::with('medicalRequest.citizen', 'medicalRequest.medicalCenter');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('psid', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(20);

        return view('pages.payments.index', compact('payments'));
    }

    /**
     * Advanced Search for Payments
     */
    public function search(Request $request)
    {
        $query = Payment::with('medicalRequest.citizen', 'medicalRequest.medicalCenter');

        // Filter by PSID or Transaction ID
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('psid', 'like', "%{$keyword}%")
                  ->orWhere('transaction_id', 'like', "%{$keyword}%");
            });
        }

        // Filter by Citizen CNIC
        if ($request->filled('cnic')) {
            $cnic = $request->cnic;
            $query->whereHas('medicalRequest.citizen', function($q) use ($cnic) {
                $q->where('cnic', 'like', "%{$cnic}%");
            });
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by Medical Center
        if ($request->filled('medical_center_id')) {
            $centerId = $request->medical_center_id;
            $query->whereHas('medicalRequest', function($q) use ($centerId) {
                $q->where('medical_center_id', $centerId);
            });
        }

        // Get results if any filter is applied, otherwise return empty or latest
        if ($request->anyFilled(['keyword', 'cnic', 'date_from', 'date_to', 'status', 'payment_method', 'medical_center_id'])) {
            $payments = $query->latest()->paginate(20)->withQueryString();
        } else {
            $payments = collect([]); // Start empty or show latest
        }

        $medicalCenters = \App\Models\MedicalCenter::all();

        return view('pages.payments.search', compact('payments', 'medicalCenters'));
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $payment->load('medicalRequest.citizen', 'medicalRequest.medicalCenter');
        
        // Log that the payment was viewed
        \App\Models\PaymentAuditLog::create([
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'action' => 'viewed',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return view('pages.payments.show', compact('payment'));
    }

    /**
     * Show the payment form for a specific PSID
     */
    public function create($psid)
    {
        $medicalRequest = MedicalRequest::where('psid', $psid)
            ->with('citizen', 'medicalCenter')
            ->firstOrFail();

        // Check if already paid
        if ($medicalRequest->isPaid()) {
            return redirect()->route('medical-requests.index')
                ->with('error', 'This medical request has already been paid.');
        }

        // Set default amount if not set
        if ($medicalRequest->amount == 0) {
            $medicalRequest->amount = 500.00; // Default amount
        }

        return view('pages.payments.create', compact('medicalRequest'));
    }

    /**
     * Process the dummy payment
     */
    public function process(Request $request)
    {
        $request->validate([
            'psid' => 'required|exists:medical_requests,psid',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,mobile_wallet,cash',
        ]);

        $medicalRequest = MedicalRequest::where('psid', $request->psid)->firstOrFail();

        // Check if already paid
        if ($medicalRequest->isPaid()) {
            return redirect()->route('medical-requests.index')
                ->with('error', 'This medical request has already been paid.');
        }

        // Set default amount if not set
        if ($medicalRequest->amount == 0) {
            $medicalRequest->amount = 500.00;
            $medicalRequest->save();
        }

        DB::beginTransaction();

        try {
            $isCash = $request->payment_method === 'cash';
            
            if ($isCash) {
                // Cash Payment Logic - Always Successful
                $transactionId = 'CASH-' . strtoupper(Str::random(8));
                $isSuccess = true;
                $status = 'success';
                $gatewayResponse = [
                    'gateway' => 'Cash Payment',
                    'response_code' => '00',
                    'response_message' => 'Cash Payment Received',
                    'timestamp' => now()->toIso8601String(),
                ];
            } else {
                // Dummy Gateway Logic
                $transactionId = 'TXN' . strtoupper(Str::random(12));
                // Simulate payment processing (80% success rate by default)
                $isSuccess = rand(1, 100) <= 80;
                $status = $isSuccess ? 'success' : 'failed';
                $gatewayResponse = [
                    'gateway' => 'Dummy Payment Gateway',
                    'response_code' => $isSuccess ? '00' : '05',
                    'response_message' => $isSuccess ? 'Transaction Successful' : 'Transaction Failed - Insufficient Funds',
                    'timestamp' => now()->toIso8601String(),
                    'card_last_four' => rand(1000, 9999),
                ];
            }

            // Create payment record
            $payment = Payment::create([
                'medical_request_id' => $medicalRequest->id,
                'psid' => $medicalRequest->psid,
                'amount' => $medicalRequest->amount,
                'transaction_id' => $transactionId,
                'payment_method' => $request->payment_method,
                'status' => $status,
                'payment_gateway_response' => $gatewayResponse,
                'paid_at' => $isSuccess ? now() : null,
            ]);

            // Update medical request payment status if successful
            if ($isSuccess) {
                $medicalRequest->payment_status = 'paid';
                $medicalRequest->save();
            }

            DB::commit();

            // Redirect based on payment status
            if ($isSuccess) {
                return redirect()->route('payments.success', $payment->id)
                    ->with('success', 'Payment processed successfully!');
            } else {
                return redirect()->route('payments.failed', $payment->id)
                    ->with('error', 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while processing payment: ' . $e->getMessage());
        }
    }

    /**
     * Show payment success page
     */
    public function success(Payment $payment)
    {
        if (!$payment->isSuccess()) {
            return redirect()->route('medical-requests.index');
        }

        $payment->load('medicalRequest.citizen', 'medicalRequest.medicalCenter');
        return view('pages.payments.success', compact('payment'));
    }

    /**
     * Show payment failure page
     */
    public function failed(Payment $payment)
    {
        if (!$payment->isFailed()) {
            return redirect()->route('medical-requests.index');
        }

        $payment->load('medicalRequest.citizen', 'medicalRequest.medicalCenter');
        return view('pages.payments.failed', compact('payment'));
    }

    /**
     * View receipt in browser (HTML)
     */
    public function viewReceipt(Payment $payment)
    {
        if (!$payment->isSuccess()) {
            return redirect()->route('medical-requests.index')
                ->with('error', 'Receipt is only available for successful payments.');
        }

        $payment->load('medicalRequest.citizen', 'medicalRequest.medicalCenter');
        return view('pages.payments.receipt', compact('payment'));
    }

    /**
     * Download standard PDF receipt
     */
    public function downloadReceipt(Payment $payment)
    {
        if (!$payment->isSuccess()) {
            return redirect()->route('medical-requests.index')
                ->with('error', 'Receipt is only available for successful payments.');
        }

        $payment->load('medicalRequest.citizen', 'medicalRequest.medicalCenter');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.payments.receipt', compact('payment'));
        
        $filename = 'receipt-' . $payment->transaction_id . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Download thermal printer optimized receipt
     */
    public function downloadThermalReceipt(Payment $payment)
    {
        if (!$payment->isSuccess()) {
            return redirect()->route('medical-requests.index')
                ->with('error', 'Receipt is only available for successful payments.');
        }

        $payment->load('medicalRequest.citizen', 'medicalRequest.medicalCenter');
        
        // Set custom paper size for 80mm thermal printer
        // 80mm = 226.77 points, height auto
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.payments.receipt-thermal', compact('payment'))
            ->setPaper([0, 0, 226.77, 841.89], 'portrait');
        
        $filename = 'thermal-receipt-' . $payment->transaction_id . '.pdf';
        
        return $pdf->download($filename);
    }
}
