<?php

namespace App\Support;

/** The list of public API routes. The API index and the console buttons both read it. */
final class ApiCatalogue
{
    /**
     * @return array<int, array{path: string, description: string}>
     */
    public static function routes(): array
    {
        return [
            ['path' => '/about', 'description' => 'Who I am and what I focus on'],
            ['path' => '/projects', 'description' => 'Published projects. Filters: featured=1, language=PHP, q=search, limit=10'],
            ['path' => '/projects?featured=1', 'description' => 'Only my featured projects'],
            ['path' => '/skills', 'description' => 'Skills, grouped'],
            ['path' => '/experience', 'description' => 'Work and education'],
            ['path' => '/contact', 'description' => 'How to reach me'],
            ['path' => '/life', 'description' => 'Away from the keyboard'],
        ];
    }
}
