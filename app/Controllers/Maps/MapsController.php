<?php

namespace App\Controllers\Maps;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class MapsController extends Controller
{
	public function getMapsView($request, $response)
	{
		return $this->view->render($response, 'maps/maps.twig');
	}
}