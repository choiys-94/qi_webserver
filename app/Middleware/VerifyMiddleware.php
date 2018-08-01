<?php

namespace App\Middleware;


class VerifyMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		$requested_path = $request->getUri()->getPath();
		if ($requested_path !== "/auth/signup") {
			unset($_SESSION['verify']);
		}
		$this->container->view->getEnvironment()->addGlobal('verify', $_SESSION['verify']);

		$response = $next($request, $response);
		return $response;
	}
}