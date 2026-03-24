<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\User\Enums\UserProfileType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserProfile
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next, string $profile): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        if ($user->profile_type?->value !== $profile) {
            abort(403, 'Perfil sem permissao para acessar este recurso.');
        }

        return $next($request);
    }
}

