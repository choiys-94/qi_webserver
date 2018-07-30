<?php

namespace App\Middleware;


class AuthMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{	
		if (!$this->container->auth->check()) {
			$this->container->flash->addMessage('error', 'Please sign in before doing that.');
			return $response->withRedirect($this->container->router->pathFor('auth.signin'));
		}
		// check if the user is not signed in
		// flash
		// redirect

		$response = $next($request, $response);
		return $response;
	}
}