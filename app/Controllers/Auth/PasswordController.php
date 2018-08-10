<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
	public function getChangePassword($request, $response)
	{
		return $this->view->render($response, 'auth/password/chpw.twig');
	}

	public function postChangePassword($request, $response)
	{
		if (!password_verify($request->getParam('old_password'), $this->auth->user()->password)) {
			$this->flash->addMessage('error', 'Old password does not matched. Please check your password.');

			return $response->withRedirect($this->router->pathFor('auth.password.chpw'));			
		}
		else if ($request->getParam('password') !== $request->getParam('password_confirm')) {
			$this->flash->addMessage('error', 'New passwords do not matched.');

			return $response->withRedirect($this->router->pathFor('auth.password.chpw'));
		}

		else if (!preg_match('/(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,50}/',$request->getParam('password'))){
			$this->flash->addMessage('error', 'Passwords must have alphabet & numeric. 8~50 length needed.');

			return $response->withRedirect($this->router->pathFor('auth.password.chpw'));
		}

		$user = $this->auth->user();
		if ($user->is_temp === '1') {
			$user->is_temp = 0;
			$user->save();		
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
			if ($userinfo->is_temp === '1') {
				$userinfo->is_temp = 0;
				$userinfo->save();		
			}
			return $response->withJson(array('message' => 'ok'));		
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}
}