<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    public $guards;

    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $this->guards = [null];
        } else {
            $this->guards = $guards;
        }

        foreach ($this->guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            $zoe = config('zoe');

            if (isset($this->guards[0]) && isset($zoe['auth'][$this->guards[0]]['login'])) {
                return route($zoe['auth'][$this->guards[0]]['login']);
            }
            return route('login');
        }
    }
}
