<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAuthUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        date_default_timezone_set("Asia/Tashkent");
        $user = Auth::user();
        if(isset($user->token) && $user->token == $request->bearerToken()){
            return $next($request);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Unauthentificated'
            ]);
        }

    }
}
