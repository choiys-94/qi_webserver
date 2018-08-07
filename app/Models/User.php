<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempUser extends Model
{
	protected $table = 'users_temp';

	protected $fillable = [
		'email',
		'username',
		'password',
		'age',
		'gender',
		'authcode',
	];

	public function setPassword($password)
	{
		$this->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
	}
}

class User extends Model
{
	protected $table = 'users';

	protected $fillable = [
		'email',
		'username',
		'password',
		'age',
		'gender',
		'token',
		'is_login',
		'is_temp',
	];

	public function setPassword($password)
	{
		$this->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
	}
}