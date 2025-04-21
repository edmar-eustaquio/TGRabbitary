<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RabbitRequest extends FormRequest
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
            'product_id' => 'nullable|exists:products,id',
            'name' => $this->status == 'For Sale' ? 'required' : 'nullable',
            'gender' => 'nullable',
            'images' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'birth' => 'nullable',
            'note' => 'nullable',
            'illness' => 'nullable',
            'status' => 'nullable',
        ];
        
    }
}
