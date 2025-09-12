<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !($request->user() instanceof Customer)) {
            return response()->json(['message' => 'Unauthorized - Customer access required'], 401);
        }

        return $next($request);
    }
}