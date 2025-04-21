<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class StringExistsInArray implements Rule
{
    protected $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function passes($attribute, $value)
    {
        return in_array($value, $this->array);
    }

    public function message()
    {
        return 'The selected value is invalid.';
    }
}
