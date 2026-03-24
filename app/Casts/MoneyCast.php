<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): int
    {
        return (int) $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        if (! is_numeric($value)) {
            throw new InvalidArgumentException("{$key} must be numeric.");
        }

        return (int) round((float) $value);
    }
}

