<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Redirect based on role stored in `roles` column
        if ($user && isset($user->roles)) {
            switch (strtolower($user->roles)) {
                case 'admin':
                    $intended = route('admin.dashboard', [], false);
                    break;
                case 'agent':
                    $intended = route('agent.dashboard', [], false);
                    break;
                default:
                    $intended = route('dashboard', [], false);
            }

            return redirect()->intended($intended);
        }

        return redirect()->intended(route('dashboard', [], false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
