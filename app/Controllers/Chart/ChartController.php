<?php

namespace App\Controllers\Chart;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class ChartController extends Controller
{
	public function getChartView($request, $response)
	{
		return $this->view->render($response, 'chart/chart.twig');
	}
}