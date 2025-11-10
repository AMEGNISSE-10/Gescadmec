<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretaryAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('secretary')->check()) {
            return redirect()->route('secretary.login');
        }

        return $next($request);
    }
}