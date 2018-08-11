<?php

namespace App\Controllers\Sensor;

use App\Models\User;
use App\Models\SensorReg;
use App\Models\SensorReal;
use App\Models\SensorAqi;
use App\Models\SensorHist;
use App\Models\Test;
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

	public function postApiRealTransfer($request, $response)
	{
		// so2, co, no2, o3, pm25, temp, lang, long, heart, timestamp, token (5초)
		try {
			$json = json_decode($request->getParam('json'));
			$so2 = $json->so2;
			$co = $json->co;
			$no2 = $json->no2;
			$o3 = $json->o3;
			$pm25 = $json->pm25;
			$temp = $json->temp;
			$lang = $json->lang;
			$long = $json->long;
			$heart = $json->heart;
			$time = $json->timestamp;
			$token = $json->token;
			$user = User::where('token', $token)->first();
			if (!($so2 && $co && $no2 && $o3 && $pm25 && $temp && $lang && $long && $heart && $time && $token)) {
				return $response->withJson(array('message' => 'Please input all of elements.'));
			}
			else if (!$user) {
				return $response->withJson(array('message' => 'Invalid token.'));
			}
			else {
				SensorReal::create([
					'so2' => $so2,
					'co' => $co,
					'no2' => $no2,
					'o3' => $o3,
					'pm25' => $pm25,
					'temp' => $temp,
					'lang' => $lang,
					'long' => $long,
					'heart' => $heart,
					'time' => date("Y-m-d H:i:s", $time),
					'real_uid' => $user->id
				]);
				return $response->withJson(array('message' => 'ok'));
			}
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}
	public function postApiAqiTransfer($request, $response)
	{
		// so2, co, no2, o3, pm25, temp, lang, long, heart, timestamp, token (5초)
		try {
			$json = json_decode($request->getParam('json'));
			$so2aqi = $json->so2aqi;
			$coaqi = $json->coaqi;
			$no2aqi = $json->no2aqi;
			$o3aqi = $json->o3aqi;
			$pm25aqi = $json->pm25aqi;
			$totalaqi = $json->totalaqi;
			$time = $json->timestamp;
			$token = $json->token;
			$user = User::where('token', $token)->first();
			if (!($so2aqi && $coaqi && $no2aqi && $o3aqi && $pm25aqi && $totalaqi && $time && $token)) {
				return $response->withJson(array('message' => 'Please input all of elements.'));
			}
			else if (!$user) {
				return $response->withJson(array('message' => 'Invalid token.'));
			}
			else {
				SensorAqi::create([
					'so2aqi' => $so2aqi,
					'coaqi' => $coaqi,
					'no2aqi' => $no2aqi,
					'o3aqi' => $o3aqi,
					'pm25aqi' => $pm25aqi,
					'totalaqi' => $totalaqi,
					'time' => date("Y-m-d H:i:s", $time),
					'aqi_uid' => $user->id
				]);
				return $response->withJson(array('message' => 'ok'));
			}
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function postApiHistoricalView($request, $response)
	{
		// 형한테 date, period, datatype,token
		// so2, co, no2, o3, pm25, temp, heart
		try {
			$json = json_decode($request->getParam('json'));
			$allow_type = array('so2', 'co', 'no2', 'o3', 'pm25', 'temp', 'heart', 'so2aqi', 'coaqi', 'no2aqi', 'o3aqi', 'pm25aqi', 'totalaqi');
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
			else {
			    $data = array();
			    for ($i = 0; $i < (int)$period; $i++) {
			    	$hist_data = SensorHist::where('hist_uid', $user->id)->where('time', 'like', $date.'%')->first();
			    	if (!$hist_data) {
			    		array_push($data, array('date' => $date, $datatype => '0'));
			    	}
			    	else {
			    		array_push($data, array('date' => $date, $datatype => $hist_data->$datatype));
			    	}
			    	$date = date('Y-m-d', strtotime("-1 day", strtotime($date)));
			    }
			}
		    
		    return $response->withJson(array('message' => 'ok', 'data' => $data));			
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function postApiHistoricalInsert($request, $response)
	{
		// 형한테 date, period, datatype,token
		// so2, co, no2, o3, pm25, temp, heart
		try {
			$json = json_decode($request->getParam('json'));
			$token = $json->token;
		    $date = $json->date;
			$user = User::where('token', $token)->first();
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid token.'));
			}
			else {
			    SensorHist::create([
			    	'so2' => $json->so2,
			    	'so2aqi' => $json->so2aqi,
			    	'co' => $json->co,
			    	'coaqi' => $json->coaqi,
			    	'no2' => $json->no2,
			    	'no2aqi' => $json->no2aqi,
			    	'o3' => $json->o3,
			    	'o3aqi' => $json->o3aqi,
			    	'pm25' => $json->pm25,
			    	'pm25aqi' => $json->pm25aqi,
			    	'totalaqi' => $json->totalaqi,
			    	'temp' => $json->temp,
			    	'heart' => $json->heart,
			    	'time' => date("Y-m-d H:i:s", $json->timestamp),
			    	'hist_uid' => $user->id
			    ]);
			}
		    return $response->withJson(array('message' => 'ok'));
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function HistoricalCalc($uid)
	{
		try {
			$today = date("Y-m-d");
			$so2 = $so2aqi = $co = $coaqi = $no2 = $no2aqi = $o3 = $o3aqi = $pm25 = $pm25aqi = $totalaqi = $temp = $heart = 0;
			$sensor_real = SensorReal::where('real_uid', $uid)->where('time', 'not like', $today.'%')->get();
			$sensor_aqi = SensorAqi::where('aqi_uid', $uid)->where('time', 'not like', $today.'%')->get();
			$hist_time = $sensor_real->first()->time;
			$sensor_real_count = $sensor_real->count();
			$sensor_aqi_count = $sensor_aqi->count();
			if ($sensor_real_count > 0 || $sensor_aqi_count > 0) {
				foreach ($sensor_real as $data) {
					$so2 += $data->so2;
					$co += $data->co;
					$no2 += $data->no2;
					$o3 += $data->o3;
					$pm25 += $data->pm25;
					$temp += $data->temp;
					$heart += $data->heart;
				}
				$so2 = round(($so2 / $sensor_real_count), 2);
				$co = round(($co / $sensor_real_count), 2);
				$no2 = round(($no2 / $sensor_real_count), 2);
				$o3 = round(($o3 / $sensor_real_count), 2);
				$pm25 = round(($pm25 / $sensor_real_count), 2);
				$temp = round(($temp / $sensor_real_count), 2);
				$heart = round(($heart / $sensor_real_count), 2);

				foreach ($sensor_aqi as $data) {
					$so2aqi += $data->so2aqi;
					$coaqi += $data->coaqi;
					$no2aqi += $data->no2aqi;
					$o3aqi += $data->o3aqi;
					$pm25aqi += $data->pm25aqi;
					$totalaqi += $data->totalaqi;
				}
				$so2aqi = round(($so2aqi / $sensor_aqi_count), 2);
				$coaqi = round(($coaqi / $sensor_aqi_count), 2);
				$no2aqi = round(($no2aqi / $sensor_aqi_count), 2);
				$o3aqi = round(($o3aqi / $sensor_aqi_count), 2);
				$pm25aqi = round(($pm25aqi / $sensor_aqi_count), 2);
				$totalaqi = round(($totalaqi / $sensor_aqi_count), 2);
				SensorHist::create([
					'so2' => $so2,
					'so2aqi' => $so2aqi,
					'co' => $co,
					'coaqi' => $coaqi,
					'no2' => $no2,
					'no2aqi' => $no2aqi,
					'o3' => $o3,
					'o3aqi' => $o3aqi,
					'pm25' => $pm25,
					'pm25aqi' => $pm25aqi,
					'totalaqi' => $totalaqi,
					'temp' => $temp,
					'heart' => $heart,
					'time' => $hist_time,
					'hist_uid' => $uid
				]);

				foreach ($sensor_real as $data) {
					$data->delete();
				}
				foreach ($sensor_aqi as $data) {
					$data->delete();
				}
			}			
		} catch (Exception $e) {

		}

	}

	public function getHistoricalView($request, $response)
	{
		// 형한테 date, period, datatype,token
		// so2, co, no2, o3, pm25, temp, heart
		try {
		    $date = $request->getParam('date');
		    $period = $request->getParam('period');
			$user = User::find($_SESSION['username']);
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid user.'));
			}
			else {
			    $data = array();
			    for ($i = 0; $i < (int)$period; $i++) {
			    	$date = date('Y-m-d', strtotime("-1 day", strtotime($date)));
			    	$hist_data = SensorHist::where('hist_uid', $user->id)->where('time', 'like', $date.'%')->first();
			    	array_push($data, array('date' => $date, 'so2' => $hist_data->so2, 'co' => $hist_data->co, 'no2' => $hist_data->no2, 'o3' => $hist_data->o3, 'pm25' => $hist_data->pm25, 'temp' => $hist_data->temp, 'heart' => $hist_data->heart, 'no2aqi' => $hist_data->no2aqi, 'so2aqi' => $hist_data->so2aqi, 'o3aqi' => $hist_data->o3aqi, 'coaqi' => $hist_data->coaqi, 'pm25aqi' => $hist_data->pm25aqi, 'totalaqi' => $hist_data->totalaqi ));
			    }
			}
		    
		    return $response->withJson($data);			
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function getRealView($request, $response)
	{
		// 형한테 date, period, datatype,token
		// so2, co, no2, o3, pm25, temp, heart
		try {
			if ($_SESSION['listview']) {
				$user = User::where('email', $_SESSION['listview'])->first();
			}
			else {
				$user = User::find($_SESSION['username']);
			}
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid user.'));
			}
			else {
			    $data = array();
		    	$real_data = SensorReal::where('real_uid', $user->id)->orderBy('time', 'desc')->first();
		    	array_push($data, array('so2' => $real_data->so2, 'co' => $real_data->co, 'no2' => $real_data->no2, 'o3' => $real_data->o3, 'pm25' => $real_data->pm25, 'temp' => $real_data->temp, 'heart' => $real_data->heart));
			}
		    
		    return $response->withJson($data);			
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function getAqiView($request, $response)
	{
		// 형한테 date, period, datatype,token
		// so2, co, no2, o3, pm25, temp, heart
		try {
			if ($_SESSION['listview']) {
				$user = User::where('email', $_SESSION['listview'])->first();
			}
			else {
				$user = User::find($_SESSION['username']);
			}
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid user.'));
			}
			else {
			    $data = array();
		    	$aqi_data = SensorAqi::where('aqi_uid', $user->id)->orderBy('time', 'desc')->first();
		    	array_push($data, array('so2aqi' => $aqi_data->so2aqi, 'coaqi' => $aqi_data->coaqi, 'no2aqi' => $aqi_data->no2aqi, 'o3aqi' => $aqi_data->o3aqi, 'pm25aqi' => $aqi_data->pm25aqi, 'totalaqi' => $aqi_data->totalaqi));
			}
		    
		    return $response->withJson($data);			
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function getSensorListView($request, $response)
	{
		return $this->view->render($response, 'Sensor/listview.twig');
	}

	public function getSensorList($request, $response)
	{
		// token
		try {
			$user = $this->auth->user();
			$devices = SensorReg::where('reg_uid', $user->id)->get();
			$data = array();

			foreach($devices as $device) {
				array_push($data, array('devicename' => $device->name, 'mac' => $device->mac));
			}

		    return $response->withJson($data);
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function postAllUserView($request, $response)
	{
		$json = json_decode($request->getParam('json'));
		try {
			$token = $json->token;
			$email = $json->email;
			$user = User::where('token', $token)->first();
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid user.'));
			}
			else {
				$specific_id = User::where('email', $email)->first()->id;
				$specific_Real = SensorReal::where('real_uid', $specific_id)->orderBy('time', 'desc')->first();
				$specific_Aqi = SensorAqi::where('aqi_uid', $specific_id)->orderBy('time', 'desc')->first();
	    		$data = array('so2' => $specific_Real->so2, 'co' => $specific_Real->co, 'no2' => $specific_Real->no2, 'o3' => $specific_Real->o3, 'pm25' => $specific_Real->pm25, 'temp' => $specific_Real->temp, 'heart' => $specific_Real->heart, 'so2aqi' => $specific_Aqi->so2aqi, 'coaqi' => $specific_Aqi->coaqi, 'no2aqi' => $specific_Aqi->no2aqi, 'o3aqi' => $specific_Aqi->o3aqi, 'pm25aqi' => $specific_Aqi->pm25aqi, 'totalaqi' => $specific_Aqi->totalaqi);
				return $response->withJson(array('message' => 'ok', 'data' => $data));
			}
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

/*
	public function postApiHistoricalView($request, $response)
	{
		// 형한테 date, period, datatype,token
		// so2, co, no2, o3, pm25, temp, heart
		try {
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
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}
*/
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