<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\TempUser;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends Controller
{
	public function getSignOut($request, $response)
	{
		var_dump($_SESSION['username']);
		die();
		$this->auth->logout();
		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getSignIn($request, $response)
	{
		return $this->view->render($response, 'auth/signin.twig');
	}

	public function postSignIn($request, $response)
	{
		$auth = $this->auth->attempt(
			$request->getParam('email'),
			$request->getParam('password')
		);

		if ($auth !== true) {
			$this->flash->addMessage('error', $auth);

			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getSignUp($request, $response)
	{
		return $this->view->render($response, 'auth/signup.twig');
	}

	public function postSignUp($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable()->length(null, 50),
			'username' => v::noWhitespace()->notEmpty()->alnum()->length(2, 12),
			'password' => v::noWhitespace()->notEmpty()->length(8, 50),
			'password_confirm' => v::noWhitespace()->notEmpty()->length(8, 50),
			'age' => v::noWhitespace()->notEmpty()->numeric()->between(1,100),
			'gender' => v::noWhitespace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		else if ($request->getParam('password') !== $request->getParam('password_confirm')) {
			$this->flash->addMessage('error', 'Passwords are unmatched!');

			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		else if (!preg_match('/(?=.*[a-z])(?=.*[0-9])[a-z0-9]/',$request->getParam('password'))){
			$this->flash->addMessage('error', 'Passwords must have alphabet and numeric.');

			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		$authcode = $this->auth->createAuthCode();
		$user = TempUser::create([
			'email' => $request->getParam('email'),
			'username' => $request->getParam('username'),
			'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
			'age' => (int)$request->getParam('age'),
			'gender' => $request->getParam('gender'),
			'authcode' => $authcode,
		]);

		$content = '<a href="http://teamc-iot.calit2.net/auth/complete?email='. $request->getParam('email') .'&authcode='. $authcode .'">verify email</a>';
		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "mailbot.team.c@gmail.com";
		$mail->Password = "qwer1234!@";
		$mail->SetFrom("teamc-iot@calit2.net");
		$mail->Subject = "TeamC-iot Sign Up Verification";
		$mail->Body = $content;
		$mail->AddAddress($request->getParam('email'));

		if (!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message has been sent";
		}

		unset($_SESSION['verify']);

		$this->flash->addMessage('success', 'You have to confirm your email.');
		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getCompleteSignUp($request, $response)
	{
		try{
			$tempuser = TempUser::where('email', $request->getParam('email'))->first();
			if ($tempuser->authcode == $request->getParam('authcode')) {
				User::create([
					'email' => $tempuser->email,
					'username' => $tempuser->username,
					'password' => $tempuser->password,
					'age' => $tempuser->age,
					'gender' => $tempuser->gender,
					'token' => $this->auth->createToken(),
					'is_login' => 0,
				]);

				$tempuser->delete();

				$this->flash->addMessage('info', 'You have been signed up!');
			}
			else {
				$this->flash->addMessage('error', 'Invalid access!');		
			}

			return $response->withRedirect($this->router->pathFor('home'));
		} catch (Exception $e) {
			$this->flash->addMessage('error', 'Error!'. $e);
			return $response->withRedirect($this->router->pathFor('home'));
		}

	}

	public function postApiSignUp($request, $response)
	{
		$json = json_decode($request->getParam('json'));

		try {
			$tempuserinfo = TempUser::where('email', $json->userEmail)->first();
			if ($tempuserinfo) {
				return $response->withJson(array('message' => 'Already proccessing. Please check your email.'));
			}
			$authcode = $this->auth->createAuthCode();
			$user = TempUser::create([
				'email' => $json->userEmail,
				'username' => $json->userNickname,
				'password' => password_hash($json->userPassword, PASSWORD_DEFAULT),
				'age' => (int)$json->userAge,
				'gender' => $json->userGender,
				'authcode' => $authcode,
			]);

			$content = '<a href="http://teamc-iot.calit2.net/auth/complete?email='. $json->userEmail .'&authcode='. $authcode .'">verify email</a>';
			$mail = new PHPMailer(); // create a new object
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = "mailbot.team.c@gmail.com";
			$mail->Password = "qwer1234!@";
			$mail->SetFrom("teamc-iot@calit2.net");
			$mail->Subject = "TeamC-iot Sign Up Verification";
			$mail->Body = $content;
			$mail->AddAddress($json->userEmail);

			if (!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Message has been sent";
			}			

			return $response->withJson(array('message' => 'ok'));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function postApiSignIn($request, $response)
	{
		$json = json_decode($request->getParam('json'));

		if (!($json->userEmail) || !($json->userPassword)) {
			return $response->withJson(array('message' => 'Please input email and password.'));
		}
		try {
			$auth = $this->auth->apiAttempt($json->userEmail, $json->userPassword);
			if ($auth == false) {
				$user = User::where('email', $json->userEmail)->first();
				$user->is_login = 1;
				$user->save();
				return $response->withJson(array('message' => 'ok', 'token' => $user->token));
			}
			else {
				return $response->withJson(array('message' => $auth));
			}			
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function getApiSignOut($request, $response)
	{
		$json = json_decode($request->getParam('json'));
		try {
			$this->auth->apiLogout($json->token);
			return $response->withJson(array('message' => 'ok'));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}
}