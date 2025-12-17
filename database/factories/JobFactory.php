<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'company_id' => Company::inRandomOrder()->first()->id,
            'details' => $this->faker->text(200),
            'location' => $this->faker->city(),      
            'salary' => $this->faker->numberBetween(7000, 20000),
            'type' => $this->faker->randomElement(['full-time', 'part-time']),
            'is_available' => $this->faker->randomElement([true, false]),
        ];
    }
}
 