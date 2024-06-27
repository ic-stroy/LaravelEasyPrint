<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompanyAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        switch ($user->role_id) {
            case 2:
                return $next($request);
                break;
            case 3:
                return redirect()->route('company_dashboard');
                break;
            case 4:
                return redirect()->route('login');
                break;
            case 1:
                return redirect()->route('dashboard');
            default;
        }
    }
}
