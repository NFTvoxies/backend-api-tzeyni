<?php

namespace App\Http\Requests\Professional\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'is_visible' => 'required|boolean',
            'is_promo' => 'required|boolean',
            'promotion_price' => 'nullable|numeric|min:0|max:999999.99',
            'images.*'=> 'required|file|mimes:png,jpg,jpeg'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            
            'description.string' => 'La description doit être une chaîne de caractères.',
            
            'time.required' => 'Le temps est obligatoire.',
            'time.string' => 'Le temps doit être une chaîne de caractères.',
            'time.max' => 'Le temps ne doit pas dépasser 255 caractères.',
            
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être supérieur ou égal à 0.',
            'price.max' => 'Le prix ne doit pas dépasser 999999.99.',
            
            'is_visible.required' => 'Le champ "visible" est obligatoire.',
            'is_visible.boolean' => 'Le champ "visible" doit être un booléen.',
            
            'is_promo.required' => 'Le champ "promotion" est obligatoire.',
            'is_promo.boolean' => 'Le champ "promotion" doit être un booléen.',
            
            'promotion_price.numeric' => 'Le prix promotionnel doit être un nombre.',
            'promotion_price.min' => 'Le prix promotionnel doit être supérieur ou égal à 0.',
            'promotion_price.max' => 'Le prix promotionnel ne doit pas dépasser 999999.99.',
            
            'images.*.required' => 'Chaque image est obligatoire.',
            'images.*.file' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Les images doivent être au format png, jpg ou jpeg.',
        ];
    }
}
