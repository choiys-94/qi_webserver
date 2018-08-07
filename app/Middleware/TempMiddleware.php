<?php

namespace App\Middleware;


class TempMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{	
		$response = $next($request, $response);
		return $response;
	}
}