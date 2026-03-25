<?php

declare(strict_types=1);

namespace App\Domains\Support\Actions;

use App\Domains\Support\Enums\SupportTicketPriority;
use App\Domains\Support\Enums\SupportTicketStatus;
use App\Domains\Support\Events\SupportTicketUpdated;
use App\Domains\Support\Models\SupportTicket;
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class OpenSupportTicketAction
{
    public function execute(User $owner, string $subject, string $description, string $category, SupportTicketPriority $priority): SupportTicket
    {
        $subject = trim($subject);
        $description = trim($description);

        if ($subject === '' || $description === '') {
            throw ValidationException::withMessages([
                'subject' => ['Assunto e descricao sao obrigatorios.'],
            ]);
        }

        /** @var SupportTicket $ticket */
        $ticket = DB::transaction(function () use ($owner, $subject, $description, $category, $priority): SupportTicket {
            $dueAt = match ($priority) {
                SupportTicketPriority::Critical => now()->addHours(2),
                SupportTicketPriority::High => now()->addHours(6),
                SupportTicketPriority::Normal => now()->addHours(24),
                SupportTicketPriority::Low => now()->addHours(48),
            };

            $ticket = SupportTicket::query()->create([
                'owner_id' => $owner->id,
                'subject' => $subject,
                'description' => $description,
                'category' => $category,
                'priority' => $priority,
                'status' => SupportTicketStatus::Open,
                'due_at' => $dueAt,
            ]);

            $ticket->messages()->create([
                'sender_id' => $owner->id,
                'message' => $description,
                'is_internal' => false,
            ]);

            return $ticket;
        });

        SupportTicketUpdated::dispatch($ticket);

        return $ticket;
    }
}
