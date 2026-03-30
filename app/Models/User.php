<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\User\Enums\UserProfileType;
use App\Domains\User\Enums\UserStatus;
use App\Domains\User\Models\User as DomainUser;

class User extends DomainUser
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_type',
        'document_number',
        'status',
        'subscription_expires_at',
        'is_exempt_from_subscription',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_type' => UserProfileType::class,
            'status' => UserStatus::class,
            'subscription_expires_at' => 'datetime',
            'is_exempt_from_subscription' => 'boolean',
        ];
    }
}
