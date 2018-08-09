<?php

namespace App\Controllers\Sensor;

use App\Models\User;
use App\Models\SensorReg;
use App\Controllers\Controller;

class SensorController extends Controller
{
	public function postApiRegistration($request, $response)
	{
		// mac, token
		try {
			$json = json_decode($request->getParam('json'));
			$token = $json->token;
			$name = $json->name;
			$mac = $json->mac;
			$user = User::where('token', $token)->first();
			$valid_mac = SensorReg::where('mac', $mac)->first();
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid token.'));
			}
			else if (!($token && $name && $mac)) {
				return $response->withJson(array('message' => 'Please input all of elements.'));
			}
			else if ($valid_mac) {
				return $response->withJson(array('message' => 'Mac addr duplicated.'));	
			}
			else {
				SensorReg::create([
					'mac' => $mac,
					'name' => $name,
					'reg_uid' => $user->id
				]);
			} 
		    return $response->withJson(array('message' => 'ok'));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function postApiDeregistration($request, $response)
	{
		// mac, token
		try {
			$json = json_decode($request->getParam('json'));
			$token = $json->token;
			$mac = $json->mac;
			$user = User::where('token', $token)->first();
			$valid_mac = SensorReg::where('mac', $mac)->first();
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid token.'));
			}
			else if (!($token && $mac)) {
				return $response->withJson(array('message' => 'Please input all of elements.'));
			}
			else if (!$valid_mac) {
				return $response->withJson(array('message' => 'Mac addr does not exist.'));	
			}
			else {
				$valid_mac->delete();
			}
		    return $response->withJson(array('message' => 'ok'));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function postApiSensorListView($request, $response)
	{
		// token
		try {
			$json = json_decode($request->getParam('json'));
			$token = $json->token;
			$user = User::where('token', $token)->first();
			$devices = SensorReg::where('reg_uid', $user->id)->get();
			$data = array();

			if (!($token)) {
				return $response->withJson(array('message' => 'Please input the token.'));
			}
			else if (!$user) {
				return $response->withJson(array('message' => 'Invalid token.'));
			}
			else if (!$devices) {
				return $response->withJson(array('message' => 'No devices.'));
			}

			foreach($devices as $device) {
				array_push($data, array('name' => $device->name, 'mac' => $device->mac));
			}

		    return $response->withJson(array('message' => 'ok', 'data' => $data));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function postApiHistoricalView($request, $response)
	{
		// 형한테 date, period, datatype,token
		// so2, co, no2, o3, pm25, temp, heart
		$json = json_decode($request->getParam('json'));
		$allow_type = array('so2', 'co', 'no2', 'o3', 'pm25', 'temp', 'heart');
		$allow_period = array('7', '30', '90');
		$token = $json->token;
		$datatype = $json->datatype;
	    $date = $json->date;
	    $period = $json->period;
		$user = User::where('token', $token)->first();
		if (!$user) {
			return $response->withJson(array('message' => 'Invalid token.'));
		}
		else if (!in_array($datatype, $allow_type)) {
			return $response->withJson(array('message' => 'Invalid datatype.'));
		}
		else if (!in_array($period, $allow_period)) {
			return $response->withJson(array('message' => 'Invalid period.'));
		}

	    $data = array();
	    for ($i = 0; $i < (int)$period; $i++) {
	    	array_push($data, array('date' => $date, $datatype => (string)rand(1, 161)));
	    	$date = date('Y-m-d', strtotime("-1 day", strtotime($date)));
	    }
	    
	    return $response->withJson(array('message' => 'ok', 'data' => $data));
	}

	public function postSensorTest($request, $response)
	{
		// so2, co, no2, o3, pm25, temp, lang, long, heart, time, token (3초)
	    $date = date('Y-m-d', strtotime("+1 day"));
	    $json = array();
	    $data = array();
	    for ($i = 0; $i < 7; $i++) {
	    	$date = date('Y-m-d', strtotime("-1 day", strtotime($date)));
//	    	array_push($data, array('date' => $date, 'so2' => (string)rand(1, 161), 'co' => (string)rand(1, 161), 'no2' => (string)rand(1, 161), 'o3' => (string)rand(1, 161), 'pm25' => (string)rand(1, 161), 'temp' => (string)rand(0, 41), 'lang' => (string)rand(1, 161), 'long' => (string)rand(1, 161), 'heart' => (string)rand(60, 121) ));
	    	array_push($data, array('date' => $date, 'so2' => (string)rand(1, 161)));
	    }
	    
	    return $response->withJson(array('message' => 'ok', 'data' => $data));
	}
}