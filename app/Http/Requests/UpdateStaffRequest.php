<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() && ($this->user()->hasRole(['super_admin','admin']));
    }

    public function rules()
    {
        $staffId = $this->route('staff')->id ?? null;

        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'belt_no' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:30',
            'email' => ['nullable','email','max:255', Rule::unique('staff','email')->ignore($staffId)],
            'cnic' => ['required','string','max:30', Rule::unique('staff','cnic')->ignore($staffId)],
            'designation_id' => 'nullable|exists:designations,id',
            'rank_id' => 'nullable|exists:ranks,id',
            'city_id' => 'nullable|exists:cities,id',
            'province_id' => 'nullable|exists:provinces,id',
        ];
    }
}
