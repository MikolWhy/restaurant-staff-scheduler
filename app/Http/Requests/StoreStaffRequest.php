<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // No Authorization required for this project
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'role' => 'required|in:server,cook,manager',
            'phone_number' => 'required|string|max:15|regex:/^\d{10,15}$/',
        ];
    }

    public function messages(): array{
        return [
            'name.regex' => 'Staff Name can only contain letters, spaces, hyphens, and apostrophes.',
            'phone_number.regex' => 'Phone number must be 10 to 15 digits.',
        ];
    }
}
