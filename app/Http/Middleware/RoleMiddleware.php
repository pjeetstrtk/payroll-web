<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Replacement for fncCheckPermission() in the original pages: only ADM/MNG users maintain data. */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        $allowed = $roles ?: config('payroll.admin_roles');
        abort_unless($user && in_array($user->user_type, $allowed, true), 403, 'You do not have permission to open this page.');

        return $next($request);
    }
}
