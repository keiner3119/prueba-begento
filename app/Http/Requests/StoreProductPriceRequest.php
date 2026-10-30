<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'currency_id' => [
                'required',
                'integer',
                'exists:currencies,id',
                // No repetir divisa para el mismo producto
                Rule::unique('product_prices')->where('product_id', $product->id),
                // La divisa base ya tiene su precio en el producto
                Rule::notIn([$product->currency_id]),
            ],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'integer' => 'El campo :attribute debe ser un entero.',
            'min' => 'El campo :attribute no puede ser negativo.',
            'max' => 'El campo :attribute excede el valor permitido.',
            'currency_id.exists' => 'La divisa indicada no existe.',
            'currency_id.unique' => 'El producto ya tiene un precio en esa divisa.',
            'currency_id.not_in' => 'Esa es la divisa base del producto.',
        ];
    }
}
