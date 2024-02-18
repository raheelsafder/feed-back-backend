<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Helper;
use Closure;

class Authenticate extends Middleware
{
    const AUTH_FAILED = 'authentication_failed';

    public function handle($request, Closure $next, ...$guards)
    {
        $authenticationResult = $this->authenticate($request, $guards);
        if ($authenticationResult === 'authentication_failed') {
            return Helper::response($request, 'Token is missing or invalid', 401);
        }
        return $next($request);
    }

    protected function authenticate($request, array $guards)
    {
        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        return self::AUTH_FAILED;
    }
}
