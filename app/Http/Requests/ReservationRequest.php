<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'time'=>'required|date'
        ];
    }
    public function messages(): array
{
    return [
        'livrable_addresse.required' => 'L\'adresse de livraison est obligatoire.',
        'livrable_addresse.string' => 'L\'adresse de livraison doit être une chaîne de caractères.',

        'time.required' => 'Le champ temps est obligatoire.',
        'time.date' => 'Le champ temps doit être une date valide.',
    ];
}
}
