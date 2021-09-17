<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Office::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'address_line1' => $this->faker->address,
            'approval_status' => Office::APPROVAL_APPROVED,
            'hidden' => false,
            'price_per_day' => $this->faker->numberBetween(1_000, 2_000),
            'monthly_discount' => 0
        ];
    }

    /**
     * Sets the Office's approval status to 'pending'.
     *
     * @return Factory
     */
    public function pending(): Factory
    {
        return $this->state(function () {
            return [
                'approval_status' => Office::APPROVAL_PENDING,
            ];
        });
    }

    /**
     * Sets the Office's approval status to 'rejected'.
     *
     * @return Factory
     */
    public function rejected(): Factory
    {
        return $this->state(function () {
            return [
                'approval_status' => Office::APPROVAL_REJECTED,
            ];
        });
    }

    /**
     * Sets the Office's visibility to 'hidden'.
     *
     * @return Factory
     */
    public function hidden(): Factory
    {
        return $this->state(function () {
            return [
                'hidden' => true,
            ];
        });
    }
}
