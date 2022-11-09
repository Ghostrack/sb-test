<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Post Model Factory
 */
class PostFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'   => fake()->realText(100),
            'content' => fake()->realText(200),
        ];

    }//end definition()


}//end class
