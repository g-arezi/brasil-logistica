<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Chat\Models\ChatThread;
use App\Domains\Freight\Models\Freight;
use App\Domains\Support\Enums\SupportTicketStatus;
use App\Domains\Support\Models\SupportTicket;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class OpsOverview extends Component
{
    public function assignToMe(string $ticketId): void
    {
        $user = auth()->user();
        $ticket = SupportTicket::query()->find($ticketId);

        if ($user === null || $ticket === null || Gate::denies('assign', $ticket)) {
            return;
        }

        $ticket->assigned_to = $user->id;
        $ticket->status = SupportTicketStatus::InProgress;
        $ticket->save();
    }

    public function setTicketStatus(string $ticketId, string $status): void
    {
        $ticket = SupportTicket::query()->find($ticketId);

        if ($ticket === null || ! in_array($status, ['open', 'in_progress', 'resolved'], true)) {
            return;
        }

        if (Gate::denies('assign', $ticket)) {
            return;
        }

        $ticket->status = SupportTicketStatus::from($status);

        if ($status === 'resolved') {
            $ticket->closed_at = now();
            $ticket->resolution_note = 'Resolvido pelo painel operacional.';
        }

        $ticket->save();
    }

    public function render(): View
    {
        $totalUsers = User::query()->count();
        $totalFreights = Schema::hasTable('freights') ? Freight::query()->count() : 0;
        $openThreads = Schema::hasTable('chat_threads') ? ChatThread::query()->where('status', 'open')->count() : 0;
        $openTickets = Schema::hasTable('support_tickets')
            ? SupportTicket::query()->whereIn('status', ['open', 'in_progress'])->count()
            : 0;

        $usersByProfile = User::query()
            ->selectRaw('profile_type, COUNT(*) as total')
            ->groupBy('profile_type')
            ->orderBy('profile_type')
            ->get()
            ->map(static function ($item) {
                $profileType = is_object($item->profile_type) && property_exists($item->profile_type, 'value')
                    ? $item->profile_type->value
                    : (string) $item->profile_type;

                return (object) [
                    'profile_type' => $profileType,
                    'total' => (int) $item->total,
                ];
            });

        $overdueTickets = 0;
        $avgFirstResponseMinutes = 0;
        $ticketQueue = collect();

        if (Schema::hasTable('support_tickets')) {
            $overdueTickets = SupportTicket::query()
                ->whereIn('status', ['open', 'in_progress'])
                ->whereNotNull('due_at')
                ->where('due_at', '<', now())
                ->count();

            $responseSamples = SupportTicket::query()
                ->whereNotNull('first_response_at')
                ->get(['created_at', 'first_response_at']);

            if ($responseSamples->isNotEmpty()) {
                $totalMinutes = $responseSamples
                    ->sum(fn (SupportTicket $ticket): int => (int) $ticket->created_at->diffInMinutes($ticket->first_response_at));

                $avgFirstResponseMinutes = (int) round($totalMinutes / max($responseSamples->count(), 1));
            }

            $ticketQueue = SupportTicket::query()
                ->with(['owner', 'assignee'])
                ->whereIn('status', ['open', 'in_progress'])
                ->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'normal' THEN 3 ELSE 4 END")
                ->orderBy('due_at')
                ->limit(12)
                ->get();
        }

        return view('livewire.ops-overview', [
            'totalUsers' => $totalUsers,
            'totalFreights' => $totalFreights,
            'openThreads' => $openThreads,
            'openTickets' => $openTickets,
            'usersByProfile' => $usersByProfile,
            'overdueTickets' => $overdueTickets,
            'avgFirstResponseMinutes' => $avgFirstResponseMinutes,
            'ticketQueue' => $ticketQueue,
        ]);
    }
}
