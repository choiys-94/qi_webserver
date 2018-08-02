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
			'password' => v::noWhitespace()->notEmpty()->length(8, 50),
			'password_confirm' => v::noWhitespace()->notEmpty()->length(8, 50),
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

		else if (!preg_match('/(?=.*[a-z])(?=.*[0-9])[a-z0-9]/',$request->getParam('password'))){
			$this->flash->addMessage('error', 'Passwords must have alphabet and numeric.');

			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		$this->auth->user()->setPassword($request->getParam('password'));

		$this->flash->addMessage('info', 'Your password was changed.');
		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function postApiChangePassword($request, $response)
	{
		$json = json_decode($request->getParam('json'));
		try {
			$userinfo = User::where('token', $json->token)->first();

			if ($userinfo->token !== $json->token) {
				return $response->withJson(array('message' => 'Invalid token! Please login.'));
			}

			if (!password_verify($json->userOldPassword, $userinfo->password)) {
				return $response->withJson(array('message' => 'Old password does not matched. Please check your password.'));
			}

			$userinfo->password = password_hash($json->userNewPassword, PASSWORD_DEFAULT);
			$userinfo->save();
			return $response->withJson(array('message' => 'ok'));		
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}
}