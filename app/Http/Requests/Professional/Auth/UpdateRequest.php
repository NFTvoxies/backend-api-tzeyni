<?php

namespace App\Http\Requests\Professional\Auth;

use Illuminate\Validation\Rule;
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
        return [
            'full_name' => 'required|string|max:200',
            'gender' => 'required|string|in:Homme,Femme|max:100',
            'email' => [
                'required',
                'email',
                'string',
                Rule::unique('professionals', 'email')->ignore(auth('professional')->id())
            ],
            'phone' => [
                'required',
                'string',
                Rule::unique('professionals', 'phone')->ignore(auth('professional')->id()),
                'max:100'
            ],
            'city' => 'required|string|max:120',
            'addresse' => 'required|string',
            'experience' => 'nullable',
            'profile' => 'nullable|file|mimes:png,jpg,jpeg',
            'card_ID' => 'nullable|string|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Le nom complet est obligatoire.',
            'full_name.string' => 'Le nom complet doit être une chaîne de caractères.',
            'full_name.max' => 'Le nom complet ne peut pas dépasser 200 caractères.',
            
            'gender.required' => 'Le genre est obligatoire.',
            'gender.in' => 'Le genre doit être soit "Male" soit "Female".',
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
            
            'profile.file' => 'Le profil doit être un fichier.',
            'profile.mimes' => 'Le fichier doit être au format png, jpg ou jpeg.',
            
            'card_ID.string' => 'La carte d\'identité doit être une chaîne de caractères.',
            'card_ID.max' => 'La carte d\'identité ne peut pas dépasser 100 caractères.',
        ];
    }
}
