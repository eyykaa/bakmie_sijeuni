<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BackofficeAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('backoffice_logged_in')) {
            return redirect()->route('backoffice.login');
        }

        return $next($request);
    }
}