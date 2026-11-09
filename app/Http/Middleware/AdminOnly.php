<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('is_admin', false)) {
            return to_route('admin.login');
        }
        return $next($request);
    }
}
