<?php

namespace App\Http\Requests\Professional\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'full_name' => 'required|string|max:200',
            'gender' => 'required|string|in:Homme,Femme|max:100',
            'email' => 'required|email|string|unique:professionals,email',
            'phone' => 'required|string|unique:professionals,phone|max:100',
            'city' => 'required|string|max:120',
            'addresse' => 'required|string',
            'password' => 'required|string|confirmed|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Le nom complet est obligatoire.',
            'full_name.string' => 'Le nom complet doit être une chaîne de caractères.',
            'full_name.max' => 'Le nom complet ne peut pas dépasser 200 caractères.',
            
            'gender.required' => 'Le genre est obligatoire.',
            'gender.in' => 'Le genre doit être soit Homme soit Femme.',
            'gender.max' => 'Le genre ne peut pas dépasser 100 caractères.',
            
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.string' => 'L\'email doit être une chaîne de caractères.',
            'email.unique' => 'Cet email est déjà utilisé.',
            
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 100 caractères.',
            
            'city.required' => 'La ville est obligatoire.',
            'city.string' => 'La ville doit être une chaîne de caractères.',
            'city.max' => 'La ville ne peut pas dépasser 120 caractères.',
            
            'addresse.required' => 'L\'adresse est obligatoire.',
            'addresse.string' => 'L\'adresse doit être une chaîne de caractères.',
            
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }
}
