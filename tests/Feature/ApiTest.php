<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_index_lists_every_endpoint(): void
    {
        $this->getJson('/api/v1')
            ->assertOk()
            ->assertJsonPath('version', 'v1')
            ->assertJsonStructure(['endpoints' => [['method', 'path', 'description']]]);
    }

    public function test_profile_routes_answer_from_config(): void
    {
        $this->getJson('/api/v1/about')->assertOk()->assertJsonPath('data.name', config('portfolio.name'));
        $this->getJson('/api/v1/skills')->assertOk()->assertJsonStructure(['data' => ['backend']]);
        $this->getJson('/api/v1/experience')->assertOk()->assertJsonStructure(['data' => [['title', 'place']]]);
        $this->getJson('/api/v1/contact')->assertOk()->assertJsonPath('data.email', config('portfolio.email'));
        $this->getJson('/api/v1/life')->assertOk();
    }

    public function test_projects_only_lists_published_ones(): void
    {
        Project::factory()->create(['slug' => 'visible-one']);
        Project::factory()->draft()->create(['slug' => 'hidden-one']);

        $this->getJson('/api/v1/projects')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'visible-one')
            ->assertJsonPath('meta.count', 1);
    }

    public function test_projects_can_be_filtered_to_featured(): void
    {
        Project::factory()->featured()->create(['slug' => 'star']);
        Project::factory()->create(['slug' => 'plain']);

        $this->getJson('/api/v1/projects?featured=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'star');
    }

    public function test_one_project_includes_its_full_description(): void
    {
        Project::factory()->create(['slug' => 'detail', 'description' => 'The long story.']);

        $this->getJson('/api/v1/projects/detail')
            ->assertOk()
            ->assertJsonPath('data.slug', 'detail')
            ->assertJsonPath('data.description', 'The long story.');
    }

    public function test_the_list_does_not_include_descriptions(): void
    {
        Project::factory()->create(['description' => 'The long story.']);

        $this->getJson('/api/v1/projects')->assertOk()->assertJsonMissingPath('data.0.description');
    }

    public function test_a_draft_is_a_json_404(): void
    {
        Project::factory()->draft()->create(['slug' => 'hidden-one']);

        $this->getJson('/api/v1/projects/hidden-one')
            ->assertNotFound()
            ->assertJsonPath('error', 'not_found');
    }

    public function test_an_unknown_route_is_a_json_404(): void
    {
        $this->getJson('/api/v1/nope')->assertNotFound()->assertJsonPath('error', 'not_found');
    }

    public function test_a_private_repository_link_is_null_in_the_api(): void
    {
        Project::factory()->create([
            'repo_url'       => 'https://gitlab.com/company/secret-repo',
            'show_repo_link' => false,
        ]);

        $this->getJson('/api/v1/projects')->assertOk()->assertJsonPath('data.0.repo', null);
    }
}
