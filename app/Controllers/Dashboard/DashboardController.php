<?php

namespace App\Controllers\Dashboard;

use App\Controllers\Controller;

class DashboardController extends Controller
{
	public function getRawView($request, $response)
	{
		return $this->view->render($response, 'dashboard/raw.twig');
	}

	public function getAqiView($request, $response)
	{
		return $this->view->render($response, 'dashboard/aqi.twig');
	}

	public function getUserListView($request, $response)
	{
		$_SESSION['listview'] = $request->getParam('email');
		return $this->view->render($response, 'dashboard/userlistview.twig', array('id' => $request->getParam('nickname')));
	}
}