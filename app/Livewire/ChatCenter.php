<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Chat\Actions\SendChatMessageAction;
use App\Domains\Chat\Models\ChatThread;
use App\Domains\User\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ChatCenter extends Component
{
    use WithFileUploads;

    public ?string $activeThreadId = null;

    public string $newMessage = '';

    public ?int $contactId = null;

    public ?TemporaryUploadedFile $attachment = null;

    #[On('chat-message-sent')]
    public function refreshThread(): void
    {
        // Evento do front apenas força re-render.
    }

    public function startThread(): void
    {
        $user = auth()->user();

        if ($user === null || $this->contactId === null) {
            return;
        }

        $allowedContactProfiles = $user->profile_type->value === 'driver'
            ? ['transportadora', 'agenciador', 'company', 'freightista']
            : ['driver'];

        $contactExists = User::query()
            ->whereKey($this->contactId)
            ->whereIn('profile_type', $allowedContactProfiles)
            ->exists();

        if (! $contactExists) {
            return;
        }

        $thread = ChatThread::query()
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $this->contactId))
            ->first();

        if ($thread === null) {
            $thread = ChatThread::query()->create([
                'created_by' => $user->id,
                'status' => 'open',
            ]);

            $thread->participants()->sync([$user->id, $this->contactId]);
        }

        $this->activeThreadId = $thread->id;
    }

    public function sendMessage(SendChatMessageAction $action): void
    {
        $user = auth()->user();

        if ($user === null || $this->activeThreadId === null) {
            return;
        }

        /** @var ChatThread|null $thread */
        $thread = ChatThread::query()->find($this->activeThreadId);

        if ($thread === null) {
            return;
        }

        if (Gate::denies('sendMessage', $thread)) {
            abort(403);
        }

        $attachmentPayload = [];

        $this->validate([
            'newMessage' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($this->attachment !== null) {
            $this->validate([
                'attachment' => [
                    'file',
                    'max:5120',
                    'mimes:pdf,jpg,jpeg,png,webp,doc,docx,txt',
                    'mimetypes:application/pdf,image/jpeg,image/png,image/webp,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain',
                ],
            ]);

            $storedPath = $this->attachment->store('attachments/chat', 'public');

            $attachmentPayload = [
                'path' => $storedPath,
                'name' => $this->attachment->getClientOriginalName(),
                'mime' => $this->attachment->getMimeType(),
                'size' => $this->attachment->getSize(),
            ];
        }

        $action->execute($thread, $user, $this->newMessage, $attachmentPayload);
        $this->newMessage = '';
        $this->attachment = null;
    }

    public function render(): View
    {
        if (! Schema::hasTable('chat_threads') || ! Schema::hasTable('chat_participants') || ! Schema::hasTable('chat_messages')) {
            return view('livewire.chat-center', [
                'threads' => collect(),
                'messages' => collect(),
                'contacts' => collect(),
                'chatUnavailable' => true,
            ]);
        }

        /** @var User $user */
        $user = auth()->user();

        $threads = ChatThread::query()
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->with(['participants', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->latest()
            ->get();

        $messages = collect();

        if ($this->activeThreadId !== null) {
            $activeThread = ChatThread::query()
                ->whereKey($this->activeThreadId)
                ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
                ->with(['messages.sender'])
                ->first();

            if ($activeThread === null) {
                $this->activeThreadId = null;
            } else {
                $messages = $activeThread->messages->sortBy('created_at')->values();
            }
        }

        $contacts = User::query()
            ->where('id', '!=', $user->id)
            ->whereIn('profile_type', $user->profile_type->value === 'driver' ? ['transportadora', 'agenciador', 'company', 'freightista'] : ['driver'])
            ->orderBy('name')
            ->get(['id', 'name', 'profile_type']);

        if ($this->activeThreadId !== null) {
            $this->dispatch('refresh-thread-channel', $this->activeThreadId);
        }

        return view('livewire.chat-center', [
            'threads' => $threads,
            'messages' => $messages,
            'contacts' => $contacts,
            'chatUnavailable' => false,
        ]);
    }
}
