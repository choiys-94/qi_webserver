<?php

namespace App\Middleware;


class VerifyMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		$this->container->view->getEnvironment()->addGlobal('verify', $_SESSION['verify']);

		$response = $next($request, $response);
		return $response;
	}
}