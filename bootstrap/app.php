<?php

ini_set("session.cookie_lifetime", 0); 
ini_set("session.cache_expire", 360); 
ini_set("session.gc_maxlifetime", 600);

session_start();

require __DIR__ . "/../vendor/autoload.php";

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
		'db' => [
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'teamc-2018summer',
			'username' => 'teamc-iot',
			'password' => 'ccasdf3fi9fsdflq',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => '',
		]
	],
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
	return $capsule;
};

$container['auth'] = function ($container) {
	return new \App\Auth\Auth;
};

$container['nav'] = function ($container) {
	return new \App\Navigation\Navigation;
};

$container['flash'] = function ($container) {
	return new \Slim\Flash\Messages;
};

$container['view'] = function($container) {
	$view = new \Slim\Views\Twig(__DIR__ . "/../resources/views", [
		'cache' => false,
	]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));

	$view->getEnvironment()->addGlobal('auth', [
		'check' => $container->auth->check(),
		'user' => $container->auth->user(),
		'userlist' => $container->auth->userlist(),
		'nickname' => $container->auth->nickname(),
		'sensorcount' => $container->auth->getSensorCount(), 
	]);

	$view->getEnvironment()->addGlobal('flash', $container->flash);
	
	return $view;
};

$container['HomeController'] = function($container) {
	return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container) {
	return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container) {
	return new \App\Controllers\Auth\PasswordController($container);
};

$container['VerifyController'] = function($container) {
	return new \App\Controllers\Auth\VerifyController($container);
};

$container['CancelController'] = function($container) {
	return new \App\Controllers\Auth\CancelController($container);
};

$container['ForgottenController'] = function($container) {
	return new \App\Controllers\Auth\ForgottenController($container);
};

$container['ChartController'] = function($container) {
	return new \App\Controllers\Chart\ChartController($container);
};

$container['MapsController'] = function($container) {
	return new \App\Controllers\Maps\MapsController($container);
};

$container['SensorController'] = function($container) {
	return new \App\Controllers\Sensor\SensorController($container);
};

$container['DashboardController'] = function($container) {
	return new \App\Controllers\Dashboard\DashboardController($container);
};

//$container['csrf'] = function ($container) {
//	return new \Slim\Csrf\Guard;
//};

$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\VerifyMiddleware($container));
//$app->add(new \App\Middleware\TempMiddleware($container));
//$app->add(new \App\Middleware\CsrfViewMiddleware($container));

//$app->add($container->csrf);

require __DIR__ . "/../app/routes.php";
