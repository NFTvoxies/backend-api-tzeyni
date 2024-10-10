<?php

namespace App\Http\Requests\Professional\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetRequest extends FormRequest
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
            'email'=>'email|required|exists:professionals,email'
        ];
    }
    public function messages(): array
    {
        return [
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.exists' => 'Cet email n\'existe pas dans notre base de données.',
        ];
    }
}
