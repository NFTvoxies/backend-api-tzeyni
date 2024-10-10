<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        'email' => ['required', 'email', 'string', 'exists:users,email'],
        'password' => ['required', 'min:8'],
    ];
}

public function messages(): array
{
    return [
        'email.required' => 'L\'adresse e-mail est obligatoire.',
        'email.email' => 'L\'adresse e-mail doit être une adresse e-mail valide.',
        'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
        'email.exists' => 'Cette adresse e-mail n\'existe pas dans nos enregistrements.',

        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
    ];
}

}
