<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domains\User\Enums\UserStatus;

final class EnsureUserProfile
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, ...$profiles): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        if ($user->status === UserStatus::Rejected || $user->status === 'rejected') {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Sua conta foi reprovada. Entre em contato com a administracao.');
        }

        if ($user->status === UserStatus::Pending || $user->status === 'pending') {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Sua conta esta pendente de aprovacao. Aguarde a liberacao do administrador.');
        }

        if ($user->profile_type?->value !== 'admin') {
            if ($user->subscription_expires_at !== null && $user->subscription_expires_at->isPast()) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                abort(403, 'Seu plano expirou. Entre em contato com a administracao para renovar os dias de acesso.');
            }
        }

        if ($user->profile_type?->value === 'admin') {
            return $next($request);
        }

        // Se usar sintaxe middleware:param1,param2 o Laravel pode ja mandar o array via $profiles
        // Caso venha como string unica com virgulas, explode
        $allowedProfiles = [];
        foreach ($profiles as $profile) {
            $parts = explode(',', (string) $profile);
            foreach ($parts as $part) {
                $allowedProfiles[] = trim($part);
            }
        }

        if (! in_array($user->profile_type?->value, $allowedProfiles, true)) {
            abort(403, 'Perfil sem permissao para acessar este recurso.');
        }

        return $next($request);
    }
}
