<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // Ya debe estar autenticado por el middleware 'auth'
        // Si no está activo, redirecciona o devuelve error
        if (! $user || ! $user->is_active) {
            // Si es una petición AJAX o API
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu cuenta está inactiva. Por favor contacta al administrador.',
                ], 403);
            }
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Para peticiones web normales (HTML)
            return redirect()->route('login')->with('error', 'Tu cuenta está inactiva. Por favor contacta al administrador.');
        }

        // Si todo bien, sigue con la petición
        return $next($request);
    }
}
