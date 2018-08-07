<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\TempUser;
use App\Controllers\Controller;

class VerifyController extends Controller
{
	public function postVerifyEmail($request, $response)
	{
		$user=User::where('email', $request->getParam('email'))->first();
		if (!preg_match('/[a-zA-z0-9\_\-]+\@[a-zA-z0-9\_\-]+\.[a-zA-z0-9\_\-]+/',$request->getParam('email'))) {
			$this->flash->addMessage('error', 'Invalid email format.');
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}
		else if ( TempUser::where('email', $request->getParam('email'))->first() ) {
			$this->flash->addMessage('error', 'Already proccessing. Please check your email.');
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}
		else if ($user) {
			$this->flash->addMessage('error', 'Email duplicated.');
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		$this->flash->addMessage('success', 'Email Available!');
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