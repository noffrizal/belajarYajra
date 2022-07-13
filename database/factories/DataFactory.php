<?php

namespace Database\Factories;

use App\Models\Data;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     *
     * @return array<string, mixed>
     */
    protected $model = Data::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'telp' => $this->faker->unique()->phoneNumber(),
            'alamat' => $this->faker->city(),
        ];
    }
}
