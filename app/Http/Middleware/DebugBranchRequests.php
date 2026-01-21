<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugBranchRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Log all requests to branches routes
        if (str_contains($request->getRequestUri(), '/branches')) {
            Log::info('Branch request received', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'headers' => $request->headers->all(),
                'data' => $request->all()
            ]);
        }
        
        return $next($request);
    }
}
