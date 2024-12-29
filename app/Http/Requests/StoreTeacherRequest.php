<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
            'employee_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nic_number' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'date_of_first_appointment' => 'required|date',
            'date_joined_to_this_school' => 'required|date',
            'appointed_subject' => 'required|string|max:255',
            'type_of_appointment' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'mobile' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
        ];
    }
}
