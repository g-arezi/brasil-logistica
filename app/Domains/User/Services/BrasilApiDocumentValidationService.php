<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use Illuminate\Support\Facades\Http;

final class BrasilApiDocumentValidationService implements DocumentValidationServiceInterface
{
    public function isValid(string $document): bool
    {
        $normalized = preg_replace('/\D+/', '', $document) ?? '';

        if (strlen($normalized) === 11) {
            return $this->validateCpf($normalized);
        }

        if (strlen($normalized) === 14) {
            return $this->validateCnpj($normalized);
        }

        return false;
    }

    private function validateCpf(string $cpf): bool
    {
        $response = Http::baseUrl((string) config('services.document_validator.base_url'))
            ->get("cpf/v1/{$cpf}");

        return $response->successful() && (bool) data_get($response->json(), 'is_valid', false);
    }

    private function validateCnpj(string $cnpj): bool
    {
        $response = Http::baseUrl((string) config('services.document_validator.base_url'))
            ->get("cnpj/v1/{$cnpj}");

        return $response->successful() && ! empty(data_get($response->json(), 'cnpj'));
    }
}

