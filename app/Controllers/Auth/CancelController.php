<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\TempUser;
use App\Controllers\Controller;

class CancelController extends Controller
{
	public function getIdCancellation($request, $response)
	{
		return $this->view->render($response, 'auth/cancel/cancellation.twig');
	}

	public function postIdCancellation($request, $response)
	{
		if (($request->getParam('password')) !== ($request->getParam('password_confirm'))) {
			$this->flash->addMessage('error', 'Please check password and confirm.');
		}
		else if (!$this->auth->cancelCheck($request->getParam('password'))) {
			$this->flash->addMessage('error', 'Sorry, password does not matched.');
		}
		else {
			$_SESSION['cancel'] = 'okddari';
			$this->flash->addMessage('confirm');
		}
		return $response->withRedirect($this->router->pathFor('auth.cancel.cancellation'));
	}

	public function getConfirmCancellation($request, $response)
	{
		if (!isset($_SESSION['cancel'])) {
			$this->flash->addMessage('error', 'Invalid access.');
			return $response->withRedirect($this->router->pathFor('home'));
		}
		else {
			$user = $this->auth->user();
			
			$user->delete();
			$this->auth->logout();
			$this->flash->addMessage('success', 'ID cancellation is successfully done.');
			return $response->withRedirect($this->router->pathFor('home'));			
		}
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