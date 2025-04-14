<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-API-Token');

        if (!$token) {
            return response()->json(['error' => 'API token required'], 401);
        }

        $apiToken = ApiToken::where('token', $token)->first();

        if (!$apiToken) {
            return response()->json(['error' => 'Invalid API token'], 401);
        }

        $apiToken->update(['last_used_at' => now()]);

        return $next($request);
    }
}
