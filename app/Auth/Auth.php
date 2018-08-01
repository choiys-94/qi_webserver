<?php

namespace App\Auth;

use App\Models\User;

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

	public function attempt($email, $password)
	{
		$user = User::where('email', $email)->first();

		if (!$user) {
			return 'ID does not exist.';
		}

		if (password_verify($password, $user->password)) {
			$_SESSION['username'] = $user->id;
			return true;
		}

		return 'Password does not matched.';
	}

	public function cancelCheck($password)
	{
		$user = User::find($_SESSION['username'])->first();

		if (password_verify($password, $user->password)) {
			return false;
		}

		return true;
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
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    
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
		unset($_SESSION['username']);
	}

	public function apiLogout()
	{
		unset($_SESSION['token']);
	}
}