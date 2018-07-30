<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
	public function getChangePassword($request, $response)
	{
		return $this->view->render($response, 'auth/password/change.twig');
	}

	public function postChangePassword($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
			'password' => v::noWhitespace()->notEmpty(),
			'password_confirm' => v::noWhitespace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.password.change'));
		}

		else if ($request->getParam('password') !== $request->getParam('password_confirm')) {
			$this->flash->addMessage('error', 'Sorry, New passwords do not match.');

			return $response->withRedirect($this->router->pathFor('auth.password.change'));
		}

		else if ($request->getParam('password_old') === $request->getParam('password')) {
			$this->flash->addMessage('error', 'Current and new password have to be different.');

			return $response->withRedirect($this->router->pathFor('auth.password.change'));
		}

		$this->auth->user()->setPassword($request->getParam('password'));

		// flash
		$this->flash->addMessage('info', 'Your password was changed.');

		// redirect
		return $response->withRedirect($this->router->pathFor('home'));
/*
		$this->auth->user()->update([
			'password' => ''
		]);
*/
	}
}