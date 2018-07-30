<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class VerifyController extends Controller
{
	public function postVerifyEmail($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
		]);

		if (!$validation->failed()) {
			$this->flash->addMessage('success', 'Email Available!');
		}
		return $response->withRedirect($this->router->pathFor('auth.signup'));
	}
}