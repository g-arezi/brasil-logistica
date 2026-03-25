<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use App\Domains\Chat\Models\ChatMessage;
use App\Domains\Chat\Models\ChatThread;
use App\Domains\Support\Models\SupportTicket;
use App\Domains\Support\Models\SupportTicketMessage;
use App\Domains\User\Enums\UserProfileType;
use App\Domains\User\Enums\UserStatus;
use App\Domains\Vehicle\Models\Vehicle;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property UserStatus $status
 * @property UserProfileType $profile_type
 * @property string|null $document_number
 * @property Carbon|null $document_verified_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'profile_type',
        'document_number',
        'document_verified_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'profile_type' => UserProfileType::class,
            'document_verified_at' => 'datetime',
        ];
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'company_id');
    }

    public function chatThreads(): BelongsToMany
    {
        return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'owner_id');
    }

    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class, 'sender_id');
    }
}
