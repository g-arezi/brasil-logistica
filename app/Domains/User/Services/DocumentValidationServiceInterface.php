<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

interface DocumentValidationServiceInterface
{
    public function isValid(string $document): bool;
}
