<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;

class CheckBlockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->user()->is_blocked == User::BLOCKED) {
            throw new Exception('User has been blocked', 403);
        }

        return $next($request);
    }
}
