<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Configured middleware handles role checks
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'cnic' => ['nullable', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'status' => ['nullable', 'in:pending,success,failed,refunded'],
            'payment_method' => ['nullable', 'in:credit_card,debit_card,bank_transfer,mobile_wallet,cash'],
            'medical_center_id' => ['nullable', 'exists:medical_centers,id'],
        ];
    }
}
