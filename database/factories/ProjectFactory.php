<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title'          => Str::title($title),
            'slug'           => Str::slug($title),
            'summary'        => fake()->sentence(10),
            'description'    => fake()->paragraph(),
            'repo_url'       => null,
            'provider'       => null,
            'show_repo_link' => true,
            'live_url'       => null,
            'language'       => 'PHP',
            'topics'         => ['laravel'],
            'cover'          => null,
            'is_featured'    => false,
            'is_published'   => true,
            'sort_order'     => 0,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
