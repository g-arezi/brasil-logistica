<?php

declare(strict_types=1);

use App\Models\User;

it('requires authentication for chat and support pages', function (): void {
    $this->get('/chat')->assertRedirect('/login');
    $this->get('/suporte')->assertRedirect('/login');
});

it('allows authenticated operational profiles to access chat and support', function (): void {
    $user = User::factory()->create([
        'profile_type' => 'driver',
        'document_number' => '32165498700',
        'status' => 'approved',
    ]);

    $this->actingAs($user)->get('/chat')->assertOk();
    $this->actingAs($user)->get('/suporte')->assertOk();
});
