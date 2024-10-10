<?php

namespace App\Http\Requests\Professional\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
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
            'name' =>'required|string',
            'description' =>'required|string',
            'brand'=> 'string|nullable',
            'price' => 'required|decimal:0,999999.99',
            'is_visible' => 'boolean|required',
            'is_featured' => 'boolean|required',
            'is_promo' => 'boolean|required',
            'promotion_price' => 'nullable|decimal:0,999999.99',
            'images.*' => 'required|file|mimes:png,jpg,jpeg',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            
            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            
            'brand.string' => 'La marque doit être une chaîne de caractères.',
            
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.between' => 'Le prix doit être compris entre 0 et 999999.99.',
            
            'is_visible.boolean' => 'Le champ "visible" doit être un booléen.',
            'is_visible.required' => 'Le champ "visible" est obligatoire.',
            
            'is_featured.boolean' => 'Le champ "en vedette" doit être un booléen.',
            'is_featured.required' => 'Le champ "en vedette" est obligatoire.',
            
            'is_promo.boolean' => 'Le champ "promotion" doit être un booléen.',
            'is_promo.required' => 'Le champ "promotion" est obligatoire.',
            
            'promotion_price.numeric' => 'Le prix promotionnel doit être un nombre.',
            'promotion_price.between' => 'Le prix promotionnel doit être compris entre 0 et 999999.99.',
            
            'images.*.required' => 'Chaque image est obligatoire.',
            'images.*.file' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Les images doivent être au format png, jpg ou jpeg.',
        ];
    }
}
