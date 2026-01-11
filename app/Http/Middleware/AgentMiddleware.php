<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (strtolower((string) ($user->roles ?? '')) !== 'agent') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
