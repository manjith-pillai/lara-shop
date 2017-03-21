<?php namespace App\Http\Middleware;

use Closure;

class ApiCallMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($request->header('api-client-id') != env('APP_KEY')) {
            return array('message' => 'Send correct api client id');
        }
        return $next($request);
	}

}
