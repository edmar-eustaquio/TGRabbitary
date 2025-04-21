<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\StringExistsInArray;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'category' => ['required', new StringExistsInArray(['Rabbit', 'Food', 'Accesories', 'Bundle Package'])],
            'desc' => 'nullable',
            'price' => 'required',
            'stock' => 'nullable',
            'active' => ['required', new StringExistsInArray([1, 0])],
            'images.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
