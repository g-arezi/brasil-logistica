<?php

declare(strict_types=1);

use App\Domains\Chat\Actions\SendChatMessageAction;
use App\Domains\Chat\Models\ChatThread;
use App\Domains\Support\Actions\OpenSupportTicketAction;
use App\Domains\Support\Enums\SupportTicketPriority;
use App\Domains\Support\Enums\SupportTicketStatus;
use App\Models\User;

it('creates a chat thread and exchanges messages', function (): void {
    config(['broadcasting.default' => 'log']);

    $driver = User::factory()->create([
        'profile_type' => 'driver',
        'document_number' => '11111111111',
    ]);

    $transportadora = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '22222222222',
    ]);

    $thread = ChatThread::query()->create([
        'created_by' => $transportadora->id,
        'status' => 'open',
    ]);

    $thread->participants()->sync([$driver->id, $transportadora->id]);

    /** @var SendChatMessageAction $action */
    $action = app(SendChatMessageAction::class);
    $message = $action->execute($thread, $driver, 'Tenho disponibilidade para esta rota.', [
        'path' => 'attachments/chat/demo.pdf',
        'name' => 'demo.pdf',
        'mime' => 'application/pdf',
        'size' => 12000,
    ]);

    expect($message->thread_id)->toBe($thread->id);
    expect($message->attachment_path)->toBe('attachments/chat/demo.pdf');
    expect($thread->messages()->count())->toBe(1);
});

it('opens and resolves a support ticket', function (): void {
    config(['broadcasting.default' => 'log']);

    $user = User::factory()->create([
        'profile_type' => 'agenciador',
        'document_number' => '33333333333',
    ]);

    /** @var OpenSupportTicketAction $action */
    $action = app(OpenSupportTicketAction::class);
    $ticket = $action->execute(
        $user,
        'Problema no envio de proposta',
        'Ao enviar uma proposta o sistema retorna erro.',
        'tecnico',
        SupportTicketPriority::High,
    );

    expect($ticket->status)->toBe(SupportTicketStatus::Open);
    expect($ticket->due_at)->not->toBeNull();

    $ticket->status = SupportTicketStatus::Resolved;
    $ticket->closed_at = now();
    $ticket->save();

    expect($ticket->fresh()->status)->toBe(SupportTicketStatus::Resolved);
});



