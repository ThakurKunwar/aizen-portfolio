<?php

namespace Database\Seeders;

use App\Enums\RepoProvider;
use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Starter projects so the site is not empty on day one.
 * Open the admin and replace them with the real thing: links, pictures, the full story.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'slug'         => 'certificate-generator',
                'title'        => 'Certificate generator',
                'summary'      => 'Organisers lose hours making certificates one by one. Paste rows from Excel, use {column} placeholders, pick a design, and download one PDF or a ZIP.',
                'language'     => 'PHP',
                'topics'       => ['laravel', 'livewire', 'dompdf'],
                'is_featured'  => true,
                'sort_order'   => 1,
            ],
            [
                'slug'         => 'guess-the-price',
                'title'        => 'Guess the Price',
                'summary'      => 'A hot-seat game where Red and Blue teams guess real product prices pulled from external APIs, so every round is new.',
                'repo_url'     => 'https://github.com/ThakurKunwar/GuessThePrice',
                'provider'     => RepoProvider::GitHub,
                'language'     => 'PHP',
                'topics'       => ['laravel', 'livewire', 'api'],
                'is_featured'  => true,
                'sort_order'   => 2,
            ],
            [
                'slug'           => 'nic-college-website',
                'title'          => 'NIC college website',
                'summary'        => 'Staff update the admission page, activities and blog themselves, because every page is built from reusable sections.',
                'language'       => 'PHP',
                'topics'         => ['laravel', 'blade'],
                'show_repo_link' => false,
                'is_featured'    => true,
                'sort_order'     => 3,
            ],
            [
                'slug'         => 'shop-shop',
                'title'        => 'shop-shop',
                'summary'      => 'Small sellers share one order link instead of taking orders in chat. In progress.',
                'repo_url'     => 'https://github.com/ThakurKunwar/shop-shop',
                'provider'     => RepoProvider::GitHub,
                'language'     => 'PHP',
                'topics'       => ['laravel'],
                'sort_order'   => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['slug' => $project['slug']], $project);
        }
    }
}
