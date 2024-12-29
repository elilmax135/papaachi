<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'logo' => 'image', // Example rule for logo image
            'favicon' => 'image', // Example rule for favicon image
            'login_img' => 'image', // Example rule for login image
            'profile' => 'image', // Example rule for profile image
            'old_color' => 'string',
            'second_old_color' => 'string',
            'main_color' => 'string',
            'second_color' => 'string',
            'tags'=> 'string',
            'company_name'=> 'required|string',
            'email'=> 'email',
            'mobile'=> 'numeric'
        ];
    }
}
