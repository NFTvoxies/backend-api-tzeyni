<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $userId = Auth::guard('user')->id(); // Get the authenticated user's ID

        return [
            'full_name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:Homme,Femme'],
            'email' => ['required', 'email', 'unique:users,email,' . $userId],
            'phone' => ['required', 'unique:users,phone,' . $userId, 'max:14'],
            'city' => ['required', 'string', 'max:200'],
            'addresse' => ['required', 'string'],
        ];
    }

    public function messages(): array
{
    return [
        'full_name.required' => 'Le nom complet est obligatoire.',
        'full_name.string' => 'Le nom complet doit être une chaîne de caractères.',
        'full_name.max' => 'Le nom complet ne peut pas dépasser 100 caractères.',

        'gender.required' => 'Le genre est obligatoire.',
        'gender.in' => 'Le genre doit être soit "Homme" soit "Femme".',

        'email.required' => 'L\'adresse e-mail est obligatoire.',
        'email.email' => 'L\'adresse e-mail doit être valide.',
        'email.unique' => 'Cette adresse e-mail est déjà utilisée.',

        'phone.required' => 'Le numéro de téléphone est obligatoire.',
        'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
        'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 14 caractères.',

        'city.required' => 'La ville est obligatoire.',
        'city.string' => 'La ville doit être une chaîne de caractères.',
        'city.max' => 'La ville ne peut pas dépasser 200 caractères.',

        'addresse.required' => 'L\'adresse est obligatoire.',
        'addresse.string' => 'L\'adresse doit être une chaîne de caractères.',
    ];
}
}
