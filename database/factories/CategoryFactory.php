<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Fantasy',
            'Science Fiction',
            'Mystery',
            'Thriller',
            'Romance',
            'Historical Fiction',
            'Non-Fiction',
            'Biography',
            'Self-Help',
            'Young Adult',
            'Children',
            'Horror',
            'Classic',
            'Adventure',
            'Dystopian',
            'Memoir',
            'Graphic Novel',
            'Poetry',
            'Crime',
            'Literary Fiction'
        ];
        return [
            'name' => $this->faker->unique()->randomElement($categories),
        ];
    }
} 