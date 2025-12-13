<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantRequest extends FormRequest
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
            'experience_level' => 'required|in:junior,mid,seniour',
            'address' => 'required|string|max:255',
            'skills' => 'nullable',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'website' => 'nullable|url|max:255',
            'company_id' => 'nullable|exists:companies,id',

        ];
    }
}
