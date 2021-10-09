<?php

namespace App\Rules;

use App\Models\Office;
use Illuminate\Contracts\Validation\Rule;

class ValidOffice implements Rule
{
    public function __construct(
        private ?Office $office
    )
    {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->office?->exists === true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Invalid office_id';
    }
}
