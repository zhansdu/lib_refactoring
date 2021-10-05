<?php

namespace App\Http\Middleware;

use App\Exceptions\ReturnResponseException;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ReturnResponseException
     */
    public function handle(Request $request, Closure $next)
    {
        if (!empty($request->user()) && !$request->user()->is_admin) {
            throw new ReturnResponseException('Unauthenticated', 403);
        }

        return $next($request);
    }
}
