<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !($request->user() instanceof User)) {
            return response()->json(['message' => 'Unauthorized - Admin access required'], 401);
        }

        return $next($request);
    }
}