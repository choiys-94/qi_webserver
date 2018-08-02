<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\TempUser;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class CancelController extends Controller
{
	public function getIdCancellation($request, $response)
	{
		return $this->view->render($response, 'auth/cancel/cancellation.twig');
	}

	public function postIdCancellation($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'password' => v::noWhitespace()->notEmpty()
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.cancel.cancellation'));
		}

		else if ($this->auth->cancelCheck($request->getParam('password'))) {
			$this->flash->addMessage('error', 'Sorry, password does not matched.');
			return $response->withRedirect($this->router->pathFor('auth.cancel.cancellation'));
		}

		$this->flash->addMessage('confirm');
		return $response->withRedirect($this->router->pathFor('auth.cancel.cancellation'));
	}

	public function getConfirmCancellation($request, $response)
	{
		$user = $this->auth->user()->first();
		
		$user->delete();
		$this->auth->logout();
		$this->flash->addMessage('success', 'ID cancellation is successfully done.');
		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function postApiIdCancellation($request, $response)
	{
		$json = json_decode($request->getParam('json'));
		try {
			$userinfo = User::where('token', $json->token)->first();

			if ($userinfo->token !== $json->token) {
				return $response->withJson(array('message' => 'Invalid token! Please login.'));
			}
			else if (!password_verify($json->userPassword, $userinfo->password)) {
				return $response->withJson(array('message' => 'Password is wrong.'));
			}
			else {
				return $response->withJson(array('message' => 'ok'));
			}

			return $response->withRedirect($this->router->pathFor('auth.cancel.cancellation'));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function getApiConfirmCancellation($request, $response)
	{
		$json = json_decode($request->getParam('json'));
		try {
			$userinfo = User::where('token', $json->token)->first();

			if ($userinfo->token !== $json->token) {
				return $response->withJson(array('message' => 'Invalid token! Please login.'));
			}

			$user = User::where('token', $json->token)->first();
			
			$user->delete();
			return $response->withJson(array('message' => 'ok'));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}
}