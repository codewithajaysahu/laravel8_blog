<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->paragraphs(1, true),
            'created_at' => $this->faker->dateTimeBetween('-3 months'),           
        ];
    }

    /**
 * Indicate that the user is suspended.
 *
 * @return \Illuminate\Database\Eloquent\Factories\Factory
 */
    public function suspended()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'This is blog title',
                'content' => 'This is blog content'
            ];
        });
    }
}
