<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'livrable_addresse' => 'required|string',
            'quantity'=>'required|integer|min:1'
        ];
    }

    public function messages(): array
{
    return [
        'livrable_addresse.required' => 'L\'adresse de livraison est obligatoire.',
        'livrable_addresse.string' => 'L\'adresse de livraison doit être une chaîne de caractères.',

        'quantity.required' => 'La quantité est obligatoire.',
        'quantity.integer' => 'La quantité doit être un nombre entier.',
        'quantity.min' => 'La quantité doit être d\'au moins 1.',
    ];
}
}
