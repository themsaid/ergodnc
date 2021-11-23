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
            'title' => $this->faker->city,
            'description' => $this->faker->paragraph,
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'address_line1' => $this->faker->address,
            'approval_status' => Office::APPROVAL_APPROVED,
            'hidden' => false,
            'price_per_day' => $this->faker->randomElement([1_000, 2_000, 3_000, 4_000]),
            'monthly_discount' => 0
        ];
    }

    public function pending(): Factory
    {
        return $this->state([
            'approval_status' => Office::APPROVAL_PENDING,
        ]);
    }

    public function hidden(): Factory
    {
        return $this->state([
            'hidden' => true,
        ]);
    }
}
