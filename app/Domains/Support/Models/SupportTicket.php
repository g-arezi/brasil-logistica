<?php

declare(strict_types=1);

namespace App\Domains\Support\Models;

use App\Domains\Support\Enums\SupportTicketPriority;
use App\Domains\Support\Enums\SupportTicketStatus;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property int $owner_id
 * @property int|null $assigned_to
 * @property SupportTicketStatus $status
 * @property SupportTicketPriority $priority
 */
class SupportTicket extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'owner_id',
        'assigned_to',
        'subject',
        'category',
        'priority',
        'status',
        'description',
        'resolution_note',
        'closed_at',
        'due_at',
        'first_response_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => SupportTicketStatus::class,
        'priority' => SupportTicketPriority::class,
        'closed_at' => 'datetime',
        'due_at' => 'datetime',
        'first_response_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id');
    }
}


