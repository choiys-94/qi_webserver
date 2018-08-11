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

class SensorReg extends Model
{
	protected $table = 'sensor_reg';

	protected $fillable = [
		'mac',
		'name', 
		'reg_uid'
	];
}

class SensorReal extends Model
{
	protected $table = 'sensor_real';

	protected $fillable = [
		'so2',
		'co',
		'no2',
		'o3',
		'pm25',
		'temp',
		'lang',
		'long',
		'heart',
		'time',
		'real_uid'
	];
}

class SensorAqi extends Model
{
	protected $table = 'sensor_aqi';

	protected $fillable = [
		'so2aqi',
		'coaqi',
		'no2aqi',
		'o3aqi',
		'pm25aqi',
		'totalaqi',
		'time',
		'aqi_uid'
	];
}

class SensorHist extends Model
{
	protected $table = 'sensor_hist';

	protected $fillable = [
		'so2',
		'so2aqi',
		'co',
		'coaqi',
		'no2',
		'no2aqi',
		'o3',
		'o3aqi',
		'pm25',
		'pm25aqi',
		'totalaqi',
		'temp',
		'heart',
		'time',
		'hist_uid'
	];
}

class Exercise extends Model
{
	protected $table = 'exercise';

	protected $fillable = [
		'starttime',
		'endtime',
		'ex_uid',
	];
}