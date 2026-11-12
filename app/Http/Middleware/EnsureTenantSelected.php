<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantSelected
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->role === UserRole::SuperAdmin && ! session('tenant_id')) {
            return redirect()->route('tenants.index')
                ->with('warning', 'Seleccione una organización antes de continuar.');
        }

        return $next($request);
    }
}
