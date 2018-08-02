<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\TempUser;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class VerifyController extends Controller
{
	public function postVerifyEmail($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable()->length(null, 50),
		]);

		if (!$validation->failed()) {
			$this->flash->addMessage('success', 'Email Available!');
		}

		$_SESSION['verify']='email';
		return $response->withRedirect($this->router->pathFor('auth.signup'));
	}

	public function postApiVerifyEmail($request, $response)
	{
		$json = json_decode($request->getParam('json'));
		try {
			if (!($json->userEmail)) {
				return $response->withJson(array('message' => 'Please input email.'));
			}
			$tempuserinfo = TempUser::where('email', $json->userEmail)->first();
			if ($tempuserinfo) {
				return $response->withJson(array('message' => 'Already proccessing. Please check your email.'));
			}

			$user = User::where('email', $json->userEmail)->first();
			if ($user) {
				return $response->withJson(array('message' => 'Email duplicated.'));
			}
			else {
				return $response->withJson(array('message' => 'ok'));
			}			
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}

	}	
}