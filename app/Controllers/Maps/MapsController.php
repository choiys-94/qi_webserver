<?php

namespace App\Controllers\Maps;

use App\Models\User;
use App\Models\Exercise;
use App\Models\SensorReal;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class MapsController extends Controller
{
	public function getMapsView($request, $response)
	{
		return $this->view->render($response, 'maps/maps.twig');
	}

	public function postApiExercise($request, $response)
	{
		$json = json_decode($request->getParam('json'));
		$token = $json->token;
		$start = $json->startTime;
		$end = $json->endTime;
		try {
			$user = User::where('token', $token)->first();
			if (!$user) {
				return $response->withJson(array('message' => 'Invalid user.'));
			}
			else {
				Exercise::create([
					'starttime' => date("Y-m-d H:i:s", $start),
					'endtime' => date("Y-m-d H:i:s", $end),
					'ex_uid' => $user->id
				]);
				return $response->withJson(array('message' => 'ok'));
			}
		} catch (Exception $e) {
			return $response->withJson(array('message' => $e));
		}
	}

	public function getExerciseView($request, $response)
	{
		$user = $this->auth->user();
		if (!$user) {
			return $response->withRedirect($this->router->pathFor('home'));
		}
		else {
			$exercise = Exercise::where('ex_uid', $user->id)->orderBy('created_at', 'desc')->first();
			$start = $exercise->starttime;
			$end = $exercise->endtime;
			$gpsdata = SensorReal::where('time', '>=', $start)->where('time', '<=', $end)->get();
			$data = array();
			foreach ($gpsdata as $gps) {
				array_push($data, array('lat' => floatval($gps->lang), 'lng' => floatval($gps->long)));
			}
			return $response->withJson($data);
		}
	}
}