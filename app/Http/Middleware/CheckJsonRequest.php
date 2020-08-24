<?php


namespace App\Http\Middleware;


use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckJsonRequest
{


    public function handle($request, Closure $next, $guard = null)
    {

        if (!$request->isJson()) {
            return response()->json([
                'error' => 'Response must be contain JSON format'
            ], 400);
        }

        return $next($request);
    }

}