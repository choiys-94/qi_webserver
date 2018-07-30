<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');

$app->group('', function () {
	$this->post('/auth/verify/email', 'VerifyController:postVerifyEmail')->setName('auth.verify.email');

	$this->get('/auth/complete', 'AuthController:getCompleteSignUp')->setName('auth.complete');
	$this->post('/auth/email', 'AuthController:postAuthEmail')->setName('auth.email');

	$this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
	$this->post('/auth/signup', 'AuthController:postSignUp');

	$this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));

$app->group('', function () {
	$this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

	$this->get('/auth/cancel/cancellation', 'CancelController:getIdCancellation')->setName('auth.cancel.cancellation');
	$this->post('/auth/cancel/cancellation', 'CancelController:postIdCancellation');

	$this->get('/auth/cancel/complete', 'CancelController:getConfirmCancellation')->setName('auth.cancel.confirm');

	$this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
	$this->post('/auth/password/change', 'PasswordController:postChangePassword');
})->add(new AuthMiddleware($container));
