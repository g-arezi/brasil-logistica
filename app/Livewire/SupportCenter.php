<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Support\Actions\OpenSupportTicketAction;
use App\Domains\Support\Enums\SupportTicketPriority;
use App\Domains\Support\Enums\SupportTicketStatus;
use App\Domains\Support\Events\SupportTicketUpdated;
use App\Domains\Support\Models\SupportTicket;
use App\Domains\User\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class SupportCenter extends Component
{
    use WithFileUploads;

    public string $subject = '';

    public string $category = 'general';

    public string $priority = 'normal';

    public string $description = '';

    public string $newMessage = '';

    public ?string $activeTicketId = null;

    public ?TemporaryUploadedFile $ticketAttachment = null;

    public ?TemporaryUploadedFile $messageAttachment = null;

    public function createTicket(OpenSupportTicketAction $action): void
    {
        $user = auth()->user();

        if ($user === null) {
            return;
        }

        $this->validate([
            'subject' => ['required', 'string', 'max:160'],
            'category' => ['required', 'in:general,financeiro,operacional,tecnico'],
            'priority' => ['required', 'in:low,normal,high,critical'],
            'description' => ['required', 'string', 'max:5000'],
        ]);

        $ticket = $action->execute(
            $user,
            $this->subject,
            $this->description,
            $this->category,
            SupportTicketPriority::from($this->priority)
        );

        if ($this->ticketAttachment !== null) {
            $this->validate([
                'ticketAttachment' => [
                    'file',
                    'max:5120',
                    'mimes:pdf,jpg,jpeg,png,webp,doc,docx,txt',
                    'mimetypes:application/pdf,image/jpeg,image/png,image/webp,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain',
                ],
            ]);

            $ticket->messages()->first()?->update([
                'attachment_path' => $this->ticketAttachment->store('attachments/support', 'public'),
                'attachment_name' => $this->ticketAttachment->getClientOriginalName(),
                'attachment_mime' => $this->ticketAttachment->getMimeType(),
                'attachment_size' => $this->ticketAttachment->getSize(),
            ]);
        }

        $this->activeTicketId = $ticket->id;
        $this->subject = '';
        $this->description = '';
        $this->priority = 'normal';
        $this->ticketAttachment = null;
    }

    public function sendMessage(): void
    {
        $user = auth()->user();

        if ($user === null || $this->activeTicketId === null || trim($this->newMessage) === '') {
            return;
        }

        /** @var SupportTicket|null $ticket */
        $ticket = SupportTicket::query()->find($this->activeTicketId);

        if ($ticket === null) {
            return;
        }

        if (Gate::denies('update', $ticket)) {
            abort(403);
        }

        $attachmentPayload = [];

        if ($this->messageAttachment !== null) {
            $this->validate([
                'messageAttachment' => [
                    'file',
                    'max:5120',
                    'mimes:pdf,jpg,jpeg,png,webp,doc,docx,txt',
                    'mimetypes:application/pdf,image/jpeg,image/png,image/webp,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain',
                ],
            ]);

            $attachmentPayload = [
                'attachment_path' => $this->messageAttachment->store('attachments/support', 'public'),
                'attachment_name' => $this->messageAttachment->getClientOriginalName(),
                'attachment_mime' => $this->messageAttachment->getMimeType(),
                'attachment_size' => $this->messageAttachment->getSize(),
            ];
        }

        $ticket->messages()->create(array_merge([
            'sender_id' => $user->id,
            'message' => trim($this->newMessage),
            'is_internal' => false,
        ], $attachmentPayload));

        $ticket->status = SupportTicketStatus::InProgress;

        if ($ticket->first_response_at === null && $ticket->owner_id !== $user->id) {
            $ticket->first_response_at = now();
        }

        $ticket->save();

        SupportTicketUpdated::dispatch($ticket);
        $this->newMessage = '';
        $this->messageAttachment = null;
    }

    public function closeTicket(): void
    {
        $user = auth()->user();

        if ($user === null || $this->activeTicketId === null) {
            return;
        }

        /** @var SupportTicket|null $ticket */
        $ticket = SupportTicket::query()->find($this->activeTicketId);

        if ($ticket === null) {
            return;
        }

        if (Gate::denies('resolve', $ticket)) {
            abort(403);
        }

        $ticket->status = SupportTicketStatus::Resolved;
        $ticket->closed_at = now();
        $ticket->resolution_note = 'Resolvido pelo painel.';
        $ticket->save();

        SupportTicketUpdated::dispatch($ticket);
    }

    public function render(): View
    {
        $user = auth()->user();

        if (! Schema::hasTable('support_tickets') || ! Schema::hasTable('support_ticket_messages')) {
            return view('livewire.support-center', [
                'tickets' => collect(),
                'activeTicket' => null,
                'messages' => collect(),
                'categories' => ['general', 'financeiro', 'operacional', 'tecnico'],
                'priorities' => SupportTicketPriority::cases(),
                'supportUnavailable' => true,
            ]);
        }

        /** @var User $user */
        $user = auth()->user();

        $ticketsQuery = SupportTicket::query()
            ->with('owner')
            ->latest();

        if ($user->profile_type->value !== 'admin') {
            $ticketsQuery->where('owner_id', $user->id);
        }

        $tickets = $ticketsQuery->get();

        $activeTicket = null;
        $messages = collect();

        if ($this->activeTicketId !== null) {
            $activeTicketQuery = SupportTicket::query()
                ->with(['messages.sender'])
                ->whereKey($this->activeTicketId);

            if ($user->profile_type->value !== 'admin') {
                $activeTicketQuery->where(function ($query) use ($user): void {
                    $query
                        ->where('owner_id', $user->id)
                        ->orWhere('assigned_to', $user->id);
                });
            }

            $activeTicket = $activeTicketQuery->first();

            if ($activeTicket === null) {
                $this->activeTicketId = null;
            } else {
                $messages = $activeTicket->messages->sortBy('created_at')->values();
            }
        }

        return view('livewire.support-center', [
            'tickets' => $tickets,
            'activeTicket' => $activeTicket,
            'messages' => $messages,
            'categories' => ['general', 'financeiro', 'operacional', 'tecnico'],
            'priorities' => SupportTicketPriority::cases(),
            'supportUnavailable' => false,
        ]);
    }
}
