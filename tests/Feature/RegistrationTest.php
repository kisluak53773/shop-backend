<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User\Mail\WelcomeEmail;
use App\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_queues_a_welcome_email_on_registration(): void
    {
        Queue::fake();

        $response = $this->postJson('api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertCreated();

        Queue::assertPushed(WelcomeEmail::class, fn(WelcomeEmail $mail): bool => $mail->user->email === 'test@example.com');
    }

    public function test_welcome_email_renders_the_user_name_and_shared_layout(): void
    {
        $user = User::factory()->create(['name' => 'Jhon']);

        $html = (new WelcomeEmail($user))->render();

        $this->assertStringContainsString('Hi Jhon', $html);
        $this->assertStringContainsString(config('app.name'), $html);
        $this->assertStringContainsString('All rights reserved', $html);
    }
}
