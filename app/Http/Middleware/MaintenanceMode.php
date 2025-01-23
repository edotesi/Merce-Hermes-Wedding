<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        if (getenv('MAINTENANCE_MODE') !== 'true') {
            return $next($request);
        }

        if ($request->query('preview') === getenv('MAINTENANCE_TOKEN')) {
            return $next($request);
        }

        return response()->view('maintenance');
    }
}
