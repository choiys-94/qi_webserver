<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');

$app->post('/api/auth/verify/email', 'VerifyController:postApiVerifyEmail');
$app->post('/api/auth/signin', 'AuthController:postApiSignIn');
$app->post('/api/auth/signup', 'AuthController:postApiSignUp');
$app->post('/api/auth/signout', 'AuthController:getApiSignOut');
$app->post('/api/auth/password/forgotten', 'ForgottenController:postApiForgotten');
$app->post('/api/auth/password/change', 'PasswordController:postApiChangePassword');
$app->post('/api/auth/cancel/cancellation', 'CancelController:postApiIdCancellation');
$app->post('/api/auth/cancel/complete', 'CancelController:getApiConfirmCancellation');

$app->group('', function () {
	$this->post('/auth/verify/email', 'VerifyController:postVerifyEmail')->setName('auth.verify.email');

	$this->get('/auth/complete', 'AuthController:getCompleteSignUp')->setName('auth.complete');

	$this->post('/auth/email', 'AuthController:postAuthEmail')->setName('auth.email');

	$this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
	$this->post('/auth/signup', 'AuthController:postSignUp');

	$this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin', 'AuthController:postSignIn');

	$this->get('/auth/password/forgotten', 'ForgottenController:getForgotten')->setName('auth.password.forgotten');
	$this->post('/auth/password/forgotten', 'ForgottenController:postForgotten');

	$this->get('/auth/password/forcomplete', 'ForgottenController:getCompleteForgotten')->setName('auth.password.forcomplete');
})->add(new GuestMiddleware($container));

$app->group('', function () {
	$this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

	$this->get('/auth/cancel/cancellation', 'CancelController:getIdCancellation')->setName('auth.cancel.cancellation');
	$this->post('/auth/cancel/cancellation', 'CancelController:postIdCancellation');

	$this->get('/auth/cancel/complete', 'CancelController:getConfirmCancellation')->setName('auth.cancel.confirm');

	$this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
	$this->post('/auth/password/change', 'PasswordController:postChangePassword');
})->add(new AuthMiddleware($container));
