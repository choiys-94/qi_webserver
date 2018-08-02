<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\TempUser;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use PHPMailer\PHPMailer\PHPMailer;

class ForgottenController extends Controller
{
	public function getForgotten($request, $response)
	{
		return $this->view->render($response, 'auth/password/forgotten.twig');
	}

	public function postForgotten($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email()->length(null, 50),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.password.forgotten'));
		}

		$userinfo = User::where('email', $request->getParam('email'))->first();
		if (!$userinfo) {
			$this->flash->addMessage('error', 'Email does not exist.');
			return $response->withRedirect($this->router->pathFor('auth.password.forgotten'));
		}

		$authcode = $this->auth->createAuthCode();
		TempUser::create([
			'email' => $userinfo->email,
			'authcode' => $authcode,
		]);

		$content = '<a href="http://teamc-iot.calit2.net/auth/password/forcomplete?email='. $request->getParam('email') .'&authcode='. $authcode .'">Click to reset password</a>';
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
		$mail->Subject = "TeamC-iot Forgotten Password";
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

	public function getCompleteForgotten($request, $response)
	{
		$tempuser = TempUser::where('email', $request->getParam('email'))->first();
		$temporary_password = $this->auth->createTemporaryPassword();
		if ($tempuser->authcode == $request->getParam('authcode')) {
			$user = User::where('email', $tempuser->email)->first();
			$user->password = password_hash($temporary_password, PASSWORD_DEFAULT);
			$user->save();
			$tempuser->delete();

			$content = 'Your temporary password is <b>'. $temporary_password .'</b>.';
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
			$mail->Subject = "TeamC-iot Temporary Password";
			$mail->Body = $content;
			$mail->AddAddress($request->getParam('email'));

			if (!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Message has been sent";
			}

			$this->flash->addMessage('info', 'Your password has been changed! Please check your email.');
			return $response->withRedirect($this->router->pathFor('home'));	
		}
		else {
			$this->flash->addMessage('error', 'Invalid access.');
			return $response->withRedirect($this->router->pathFor('home'));	
		}
	}

	public function postApiForgotten($request, $response)
	{
		$json = json_decode($request->getParam('json'));

		try {
			$userinfo = User::where('email', $json->userEmail)->first();

			if ($userinfo->email !== $json->userEmail) {
				return $response->withJson(array('message' => 'Email does not exist.'));
			}

			$tempuserinfo = TempUser::where('email', $json->userEmail)->first();
			if ($tempuserinfo) {
				return $response->withJson(array('message' => 'Already proccessing. Please check your email.'));
			}

			$authcode = $this->auth->createAuthCode();
			TempUser::create([
				'email' => $userinfo->email,
				'authcode' => $authcode,
			]);

			$content = '<a href="http://teamc-iot.calit2.net/auth/password/forcomplete?email='. $json->userEmail .'&authcode='. $authcode .'">Click to reset password</a>';
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
			$mail->Subject = "TeamC-iot Forgotten Password";
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
}