<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_message_is_saved(): void
    {
        $this->post('/contact', [
            'name'    => 'Sita Sharma',
            'email'   => 'sita@example.com',
            'message' => 'Hello, I would like to talk about a role.',
        ])->assertRedirect()->assertSessionHas('status');

        $this->assertDatabaseHas('contact_messages', ['email' => 'sita@example.com', 'name' => 'Sita Sharma']);
    }

    public function test_the_honeypot_catches_bots_without_telling_them(): void
    {
        $this->post('/contact', [
            'name'    => 'Spam Bot',
            'email'   => 'bot@example.com',
            'message' => 'Buy cheap things at my website now.',
            'website' => 'http://spam.example',
        ])->assertRedirect()->assertSessionHas('status');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_bad_input_is_refused(): void
    {
        $this->post('/contact', ['name' => '', 'email' => 'not-an-email', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_a_line_break_in_the_name_cannot_reach_the_email_headers(): void
    {
        $this->post('/contact', [
            'name'    => "Eve\r\nBcc: evil@example.com",
            'email'   => 'eve@example.com',
            'message' => 'This is a normal looking message body.',
        ]);

        $this->assertSame('Eve Bcc: evil@example.com', ContactMessage::first()->name);
    }

    public function test_people_cannot_flood_the_form(): void
    {
        $payload = ['name' => 'Flood', 'email' => 'f@example.com', 'message' => 'Message number one, long enough.'];

        $this->post('/contact', $payload)->assertRedirect();
        $this->post('/contact', $payload)->assertRedirect();
        $this->post('/contact', $payload)->assertStatus(429);

        $this->assertSame(2, ContactMessage::count());
    }
}
