<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMustChangePassword
{
    /**
     * If the user was force-reset by Super Admin, redirect to change password page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Allow access to change-password route and logout only
            if (! $request->routeIs('change-password') && ! $request->routeIs('logout')) {
                return redirect()->route('change-password')
                    ->with('warning', 'Anda harus mengganti password sebelum melanjutkan.');
            }
        }

        return $next($request);
    }
}
