<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'leave_start' => 'required|date|after_or_equal:today',
            'leave_end' => 'required|date|after_or_equal:leave_start',
            'type' => 'required|in:ziek,verlof',
            'reason' => 'nullable|string|max:255',
        ];
    }
}
