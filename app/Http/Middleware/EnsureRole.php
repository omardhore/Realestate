<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:admin|agent')
     */
    public function handle(Request $request, Closure $next, string $roles = null)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        // roles parameter may be pipe-separated or comma-separated
        $accepted = preg_split('/[|,]/', $roles);
        $accepted = array_map('trim', $accepted);
        $userRole = strtolower((string) ($user->roles ?? ''));

        foreach ($accepted as $r) {
            if ($userRole === strtolower($r)) {
                return $next($request);
            }
        }

        abort(403);
    }
}
