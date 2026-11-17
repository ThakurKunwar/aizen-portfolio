<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/admin/projects')->assertRedirect(route('admin.login'));
        $this->post('/admin/projects', [])->assertRedirect(route('admin.login'));
    }

    public function test_the_admin_can_log_in(): void
    {
        $user = User::factory()->create(); // the factory password is "password"

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('admin.projects.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_a_wrong_password_is_refused(): void
    {
        $user = User::factory()->create();

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'nope'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_the_admin_can_create_a_project_and_the_provider_is_detected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/projects', [
                'title'        => 'My Project',
                'summary'      => 'A short summary.',
                'repo_url'     => 'https://github.com/ThakurKunwar/shop-shop',
                'topics'       => 'laravel, php',
                'is_published' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'title'    => 'My Project',
            'slug'     => 'my-project',
            'provider' => 'github',
        ]);

        $this->assertSame(['laravel', 'php'], Project::first()->topics);
    }

    public function test_two_projects_with_the_same_title_get_different_slugs(): void
    {
        $admin = User::factory()->create();
        $data = ['title' => 'Same Title', 'summary' => 'Summary.', 'is_published' => 1];

        $this->actingAs($admin)->post('/admin/projects', $data);
        $this->actingAs($admin)->post('/admin/projects', $data);

        $this->assertDatabaseHas('projects', ['slug' => 'same-title']);
        $this->assertDatabaseHas('projects', ['slug' => 'same-title-2']);
    }

    public function test_title_and_summary_are_required(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/projects', [])
            ->assertSessionHasErrors(['title', 'summary']);
    }

    public function test_a_link_that_is_not_a_url_is_refused(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/projects', ['title' => 'X', 'summary' => 'Y', 'repo_url' => 'not a link'])
            ->assertSessionHasErrors('repo_url');
    }

    public function test_the_admin_can_update_a_project(): void
    {
        $project = Project::factory()->create(['title' => 'Old', 'slug' => 'old']);

        $this->actingAs(User::factory()->create())
            ->put("/admin/projects/{$project->id}", [
                'title'   => 'New title',
                'slug'    => 'old',
                'summary' => 'New summary.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'New title', 'summary' => 'New summary.']);
    }

    public function test_publish_and_featured_switches_flip(): void
    {
        $project = Project::factory()->create(['is_published' => true, 'is_featured' => false]);
        $admin = User::factory()->create();

        $this->actingAs($admin)->patch("/admin/projects/{$project->id}/toggle/published")->assertRedirect();
        $this->actingAs($admin)->patch("/admin/projects/{$project->id}/toggle/featured")->assertRedirect();

        $project->refresh();
        $this->assertFalse($project->is_published);
        $this->assertTrue($project->is_featured);
    }

    public function test_an_unknown_switch_is_a_404(): void
    {
        $project = Project::factory()->create();

        $this->actingAs(User::factory()->create())
            ->patch("/admin/projects/{$project->id}/toggle/everything")
            ->assertNotFound();
    }

    public function test_the_admin_can_delete_a_project(): void
    {
        $project = Project::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete("/admin/projects/{$project->id}")
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_the_form_pages_load(): void
    {
        $project = Project::factory()->create();
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/projects')->assertOk();
        $this->actingAs($admin)->get('/admin/projects/create')->assertOk();
        $this->actingAs($admin)->get("/admin/projects/{$project->id}/edit")->assertOk();
        $this->actingAs($admin)->get('/admin/messages')->assertOk();
    }
}
