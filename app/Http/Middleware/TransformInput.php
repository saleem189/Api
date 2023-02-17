<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $transformer)
    {
        $transformedInput = [];
        foreach ($request->request->all() as $input => $value) { // $request->request->all()   here it is trying to get all elements of the body of request
            $transformedInput[$transformer::orignalAttribute($input)] = $value;
        }
        $request->replace($transformedInput);
        return $next($request);
    }
}
