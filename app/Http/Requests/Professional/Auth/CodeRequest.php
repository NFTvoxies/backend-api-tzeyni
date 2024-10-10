<?php

namespace App\Http\Requests\Professional\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CodeRequest extends FormRequest
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
            'code'=>['required','min:6']
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Le champ code est obligatoire.',
            'code.min' => 'Le code doit contenir au moins 6 caractères.',
        ];
    }
}
