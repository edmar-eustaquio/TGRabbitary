<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RabbitBreedRequest extends FormRequest
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
            'buck_id' => 'nullable|exists:rabbits,id',
            'doe_id' => 'nullable|exists:rabbits,id',
            'tested' => 'nullable',
            'bred' => 'nullable',
            'breed_duration' => 'nullable',
            'out_count' => 'nullable',
            'kindled' => 'nullable',
            'weaning' => 'nullable',
            'alive' => 'nullable',
            'dead' => 'nullable',
            'expected_kindle' => 'nullable',
        ];
        
    }
}
