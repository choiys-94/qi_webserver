<?php

namespace App\Auth;

use App\Models\User;
use App\Models\SensorReg;

class Auth
{
	public function user()
	{
		return User::find($_SESSION['username']);
	}

	public function check()
	{
		return isset($_SESSION['username']);
	}

	public function userlist()
	{
		$user = User::where('is_login', 'not like', '0')->get();
		$userlist = array();
		foreach ($user as $u) {
			array_push($userlist, array('email' => $u->email, 'nickname' => $u->username));
		}
		return $userlist;
	}

	public function getSensorCount()
	{
		return SensorReg::where('reg_uid', $this->user()->id)->count();
	}

	public function nickname()
	{
		return $_SESSION['nickname'];
	}

	public function attempt($email, $password)
	{
		$user = User::where('email', $email)->first();

		if (!$user) {
			return 'ID does not exist.';
		}

		else if (password_verify($password, $user->password)) {
			$_SESSION['username'] = $user->id;
			return true;
		}

		return 'Password does not matched.';
	}

	public function apiAttempt($email, $password)
	{
		$user = User::where('email', $email)->first();

		if (!$user) {
			return 'ID does not exist.';
		}

		else if (password_verify($password, $user->password)) {
			if ($user->is_login === 1) {
				return 'Already logged in.';
			}
			return false;
		}

		return 'Password does not matched.';
	}

	public function cancelCheck($password)
	{
		$user = User::find($_SESSION['username']);
		if (password_verify($password, $user->password)) {
			return true;
		}

		return false;
	}

	public function createAuthCode($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    
	    return $randomString;
	}

	public function createTemporaryPassword($length = 8) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length-1; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    $randomString .= $characters[rand(0, 9)];
	    return $randomString;
	}

	public function createToken($length = 32) {
	    $characters = '0123456789abcdef';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    
	    return $randomString;
	}

	public function logout()
	{
		$user = User::find($_SESSION['username']);
		if ($user->is_login !== 0) {
			$user->is_login = $user->is_login-1;
			$user->save();
		}
		session_destroy();
	}

	public function apiLogout($token)
	{
		$user = User::where('token', $token)->first();
		if ($user->is_login !== 0) {
			$user->is_login = $user->is_login-1;
			$user->save();
		}
		session_destroy();
	}
}