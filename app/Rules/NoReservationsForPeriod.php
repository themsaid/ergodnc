<?php

namespace App\Rules;

use App\Models\Office;
use Illuminate\Contracts\Validation\Rule;

class NoReservationsForPeriod implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
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
        return $this->office && ! $this->office
            ->reservations()
            ->activeBetween(request('start_date'), request('end_date'))
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'You cannot make a reservation during this time';
    }
}
