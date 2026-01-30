<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfUnauthorized
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!$request->user() || !$request->user()->can($permission)) {
            return redirect()
                ->route('dashboard.index')
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}