<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

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
        try {
            $response = Http::baseUrl((string) config('services.document_validator.base_url'))
                ->timeout(5)
                ->connectTimeout(3)
                ->retry(1, 200)
                ->get("cpf/v1/{$cpf}");
        } catch (Throwable) {
            return false;
        }

        return $response->successful() && (bool) data_get($response->json(), 'is_valid', false);
    }

    private function validateCnpj(string $cnpj): bool
    {
        try {
            $response = Http::baseUrl((string) config('services.document_validator.base_url'))
                ->timeout(5)
                ->connectTimeout(3)
                ->retry(1, 200)
                ->get("cnpj/v1/{$cnpj}");
        } catch (Throwable) {
            return false;
        }

        return $response->successful() && ! empty(data_get($response->json(), 'cnpj'));
    }
}
