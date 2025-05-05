<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMfaVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('mfa_verified')) {
            return redirect('/mfa');
        }

        return $next($request);
    }
}
