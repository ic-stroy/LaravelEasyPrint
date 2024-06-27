<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && $user->role_id) {
            if (in_array($user->role_id, [2, 3])) {
                return redirect()->route('company_dashboard');
            }elseif ($user->role_id == 1) {
                return $next($request);
            }else{
                auth()->logout();
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }

    }
}
