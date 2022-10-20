<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name"=>$this->faker->name(),
            "content"=>Str::random(100),
            "image"=>$this->faker->imageUrl(900,300),
            "size"=>$this->faker->randomDigit()
        ];
    }
}
