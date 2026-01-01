<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentExportController extends Controller
{
    public function excel(\Illuminate\Http\Request $request)
    {
        $payments = $this->getFilteredPayments($request);
        
        $export = new class($payments) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $payments;
            
            public function __construct($payments) {
                $this->payments = $payments;
            }
            
            public function collection() {
                return $this->payments->map(function($payment) {
                    return [
                        'Payment ID' => $payment->id,
                        'Transaction ID' => $payment->transaction_id,
                        'PSID' => $payment->psid,
                        'Citizen Name' => $payment->medicalRequest->citizen->full_name ?? 'N/A',
                        'CNIC' => $payment->medicalRequest->citizen->cnic ?? 'N/A',
                        'Medical Center' => $payment->medicalRequest->medicalCenter->name ?? 'N/A',
                        'Amount (PKR)' => $payment->amount,
                        'Payment Method' => ucwords(str_replace('_', ' ', $payment->payment_method)),
                        'Status' => ucfirst($payment->status),
                        'Payment Date' => $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'N/A',
                        'Created At' => $payment->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            }
            
            public function headings(): array {
                return [
                    'Payment ID', 'Transaction ID', 'PSID', 'Citizen Name', 'CNIC', 
                    'Medical Center', 'Amount (PKR)', 'Payment Method', 'Status', 
                    'Payment Date', 'Created At'
                ];
            }
        };
        
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'payments_' . date('Y-m-d') . '.xlsx');
    }

    public function csv(\Illuminate\Http\Request $request)
    {
        $payments = $this->getFilteredPayments($request);
        
        $export = new class($payments) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $payments;
            
            public function __construct($payments) {
                $this->payments = $payments;
            }
            
            public function collection() {
                return $this->payments->map(function($payment) {
                    return [
                        $payment->id,
                        $payment->transaction_id,
                        $payment->psid,
                        $payment->medicalRequest->citizen->full_name ?? 'N/A',
                        $payment->medicalRequest->citizen->cnic ?? 'N/A',
                        $payment->medicalRequest->medicalCenter->name ?? 'N/A',
                        $payment->amount,
                        ucwords(str_replace('_', ' ', $payment->payment_method)),
                        ucfirst($payment->status),
                        $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'N/A',
                        $payment->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            }
            
            public function headings(): array {
                return [
                    'Payment ID', 'Transaction ID', 'PSID', 'Citizen Name', 'CNIC', 
                    'Medical Center', 'Amount (PKR)', 'Payment Method', 'Status', 
                    'Payment Date', 'Created At'
                ];
            }
        };
        
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'payments_' . date('Y-m-d') . '.csv');
    }

    protected function getFilteredPayments(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Payment::with('medicalRequest.citizen', 'medicalRequest.medicalCenter');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->latest()->get();
    }
}
