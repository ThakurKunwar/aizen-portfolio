<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_with_featured_projects(): void
    {
        Project::factory()->featured()->create(['title' => 'Shown on home', 'slug' => 'shown-on-home']);

        $this->get('/')
            ->assertOk()
            ->assertSee(config('portfolio.name'))
            ->assertSee('Shown on home');
    }

    public function test_home_page_loads_with_no_projects(): void
    {
        $this->get('/')->assertOk()->assertSee('No projects yet');
    }

    public function test_project_page_shows_a_published_project_and_hides_a_draft(): void
    {
        Project::factory()->create(['title' => 'Public one', 'slug' => 'public-one']);
        Project::factory()->draft()->create(['slug' => 'secret-one']);

        $this->get('/projects/public-one')->assertOk()->assertSee('Public one');
        $this->get('/projects/secret-one')->assertNotFound();
    }

    public function test_project_list_can_be_searched(): void
    {
        Project::factory()->create(['title' => 'Alpha tool', 'slug' => 'alpha-tool']);
        Project::factory()->create(['title' => 'Beta game', 'slug' => 'beta-game']);

        $this->get('/projects?q=alpha')
            ->assertOk()
            ->assertSee('Alpha tool')
            ->assertDontSee('Beta game');
    }

    public function test_a_private_repository_link_is_never_shown(): void
    {
        Project::factory()->create([
            'slug'           => 'company-work',
            'repo_url'       => 'https://gitlab.com/company/secret-repo',
            'show_repo_link' => false,
        ]);

        $this->get('/projects/company-work')->assertOk()->assertDontSee('secret-repo');
    }

    public function test_cv_page_loads(): void
    {
        $this->get('/cv')->assertOk()->assertSee(config('portfolio.name'));
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }
}
