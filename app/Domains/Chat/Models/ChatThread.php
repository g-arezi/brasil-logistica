<?php

declare(strict_types=1);

namespace App\Domains\Chat\Models;

use App\Domains\Chat\Enums\ChatThreadStatus;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property int $created_by
 * @property ChatThreadStatus $status
 */
class ChatThread extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'freight_id',
        'created_by',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ChatThreadStatus::class,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'thread_id', 'user_id')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'thread_id');
    }
}

