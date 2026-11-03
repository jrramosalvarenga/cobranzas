<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentTenant
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->role === UserRole::SuperAdmin) {
            return $next($request);
        }

        if (! $user->tenant_id) {
            abort(403, 'Usuario sin organización asignada.');
        }

        if (! $user->tenant?->active) {
            abort(403, 'La organización está desactivada.');
        }

        return $next($request);
    }
}
