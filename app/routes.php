<?php

use App\Middleware\AuthMiddleware;

// App User Management
$app->post('/api/auth/verify/email', 'VerifyController:postApiVerifyEmail');
$app->post('/api/auth/signin', 'AuthController:postApiSignIn');
$app->post('/api/auth/signup', 'AuthController:postApiSignUp');
$app->post('/api/auth/signout', 'AuthController:getApiSignOut');
$app->post('/api/auth/password/forgotten', 'ForgottenController:postApiForgotten');
$app->post('/api/auth/password/change', 'PasswordController:postApiChangePassword');
$app->post('/api/auth/cancel/cancellation', 'CancelController:postApiIdCancellation');
$app->post('/api/auth/cancel/complete', 'CancelController:getApiConfirmCancellation');

// App Sensor
$app->post('/api/sensor/historical/view', 'SensorController:postApiHistoricalView');
$app->post('/api/sensor/registration', 'SensorController:postApiRegistration');
$app->post('/api/sensor/deregistration', 'SensorController:postApiDeregistration');
$app->post('/api/sensor/listview', 'SensorController:postApiSensorListView');
$app->post('/api/sensor/real/transfer', 'SensorController:postApiRealTransfer');

// Home
$app->get('/', 'HomeController:index')->setName('home');

$app->post('/auth/verify/email', 'VerifyController:postVerifyEmail')->setName('auth.verify.email');

$app->get('/auth/complete', 'AuthController:getCompleteSignUp')->setName('auth.complete');

$app->post('/auth/email', 'AuthController:postAuthEmail')->setName('auth.email');
$app->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');

$app->post('/auth/signup', 'AuthController:postSignUp');

$app->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
$app->post('/auth/signin', 'AuthController:postSignIn');

$app->get('/auth/password/resetpw', 'ForgottenController:getForgotten')->setName('auth.password.resetpw');
$app->post('/auth/password/resetpw', 'ForgottenController:postForgotten');

$app->get('/auth/password/forcomplete', 'ForgottenController:getCompleteForgotten')->setName('auth.password.forcomplete');

$app->get('/chart/view', 'ChartController:getChartView')->setName('chart.view');
$app->get('/maps/view', 'MapsController:getMapsView')->setName('maps.view');

$app->post('/sensor/test', 'SensorController:postSensorTest')->setName('sensor.test');

$app->group('', function () {
	$this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

	$this->get('/auth/cancel/cancellation', 'CancelController:getIdCancellation')->setName('auth.cancel.cancellation');
	$this->post('/auth/cancel/cancellation', 'CancelController:postIdCancellation');

	$this->get('/auth/cancel/complete', 'CancelController:getConfirmCancellation')->setName('auth.cancel.complete');

	$this->get('/auth/password/chpw', 'PasswordController:getChangePassword')->setName('auth.password.chpw');
	$this->post('/auth/password/chpw', 'PasswordController:postChangePassword');
})->add(new AuthMiddleware($container));
