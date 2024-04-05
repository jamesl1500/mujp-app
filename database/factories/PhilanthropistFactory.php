<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Philanthropist;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhilanthropistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Philanthropist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $year_of_birth = rand(1700,1931);
        $year_of_death = $year_of_birth + rand(65, 90);
        $city_of_birth = City::inRandomOrder()->first()->id;

        return [
            'city_of_birth' => $city_of_birth,
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'year_of_birth' => $year_of_birth,
            'month_of_birth' => $this->faker->month(),
            'date_of_birth' => $this->faker->dayOfMonth(),
            'jewish_year_of_birth' => $year_of_birth,
            'jewish_month_of_birth' => $this->faker->month(),
            'jewish_date_of_birth' => $this->faker->dayOfMonth(),
            'year_of_death' => $year_of_death,
            'month_of_death' => $this->faker->month(),
            'date_of_death' => $this->faker->dayOfMonth(),
            'jewish_year_of_death' => $year_of_death,
            'jewish_month_of_death' => $this->faker->month(),
            'jewish_date_of_death' => $this->faker->dayOfMonth(),
            'status' => 'active',
            'created_by' => 1,
        ];
    }
}
