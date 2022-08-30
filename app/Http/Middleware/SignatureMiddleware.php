<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $headerName = 'X-Name')
    {
        $response =  $next($request);
        $response->header($headerName, config('app.name'));
        return $response;
    }
}
