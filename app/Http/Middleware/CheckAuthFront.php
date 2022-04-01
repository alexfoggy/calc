<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuthFront
{
    /**
     * User should be redirected if are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    public function handle($request, Closure $next)
	{
		if (!ifUserLogIn()) return redirect('/');

		return $next($request);
	}
}
