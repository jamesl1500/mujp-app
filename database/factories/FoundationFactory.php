<?php

namespace Database\Factories;

use App\Models\Foundation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoundationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Foundation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company . 'Foundation'
        ];
    }
}
