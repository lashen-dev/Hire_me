<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name' => $this->faker->company(),
            'location' => $this->faker->city(),
            'website' => $this->faker->url(),
            'logo' => $this->faker->imageUrl(640, 480, 'business'),
            'description' => $this->faker->text(200),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            
        ];
    }
}
