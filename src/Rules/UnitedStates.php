<?php

namespace Dillingham\Locality\Rules;

use Dillingham\Locality\Facades\Locality;
use Illuminate\Contracts\Validation\Rule;

class UnitedStates implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, array_keys(Locality::states()));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Must be a 2 letter USA state.';
    }
}
