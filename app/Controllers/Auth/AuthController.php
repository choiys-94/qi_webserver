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

		if (!$auth) {
			$this->flash->addMessage('error', 'Could not sign you in with those details.');
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
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
			'username' => v::noWhitespace()->notEmpty()->alnum(),
			'password' => v::noWhitespace()->notEmpty(),
			'password_confirm' => v::noWhitespace()->notEmpty(),
			'age' => v::noWhitespace()->notEmpty()->numeric()->between(1,100),
			'gender' => v::numeric()->between(0, 1),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		else if ($request->getParam('password') !== $request->getParam('password_confirm')) {
			$this->flash->addMessage('error', 'Passwords are unmatched!');

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
		$mail->Subject = "TeamC-iot Test Mail";
		$mail->Body = $content;
		$mail->AddAddress($request->getParam('email'));

		if (!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message has been sent";
		}

		$this->flash->addMessage('success', 'You have to confirm your email.');
		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getCompleteSignUp($request, $response)
	{
		$tempuser = TempUser::where('email', $request->getParam('email'))->first();
		if ($tempuser->authcode == $request->getParam('authcode')) {
			$user = User::create([
				'email' => $tempuser->email,
				'username' => $tempuser->username,
				'password' => $tempuser->password,
				'age' => $tempuser->age,
				'gender' => $tempuser->gender,
			]);

			$tempuser->delete();

			$this->flash->addMessage('info', 'You have been signed up!');

//			$this->auth->attempt($user->email, $request->getParam('password')); 

			return $response->withRedirect($this->router->pathFor('home'));	
		}
	}

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
			$this->flash->addMessage('error', 'Sorry, password do not match.');
			return $response->withRedirect($this->router->pathFor('auth.cancel.cancellation'));
		}

		$this->flash->addMessage('confirm');
		return $response->withRedirect($this->router->pathFor('auth.cancel.cancellation'));
	}
}