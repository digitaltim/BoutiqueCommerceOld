<?php
use It_All\BoutiqueCommerce\Middleware\AuthMiddleware;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$slim->get('/', 'It_All\BoutiqueCommerce\Controllers\HomeController:index')->setName('home');

$slim->get('/' . $config['dirs']['admin'], 'It_All\BoutiqueCommerce\Controllers\AuthController:getSignIn')->setName('auth.signin');
$slim->post('/' . $config['dirs']['admin'], 'It_All\BoutiqueCommerce\Controllers\AuthController:postSignIn');

$slim->get('/' . $config['dirs']['admin'] . '/signup', 'It_All\BoutiqueCommerce\Controllers\AuthController:getSignUp')->setName('auth.signup');
$slim->post('/' . $config['dirs']['admin'] . '/signup', 'It_All\BoutiqueCommerce\Controllers\AuthController:postSignUp');

// Group routes that a user needs to be signed in to access
$slim->group('', function () {
	$container = $this->getContainer();
	$settings = $container->get('settings');

	$this->get('/' . $settings['dirs']['admin'] . '/signout', 'It_All\BoutiqueCommerce\Controllers\AuthController:getSignOut')->setName('auth.signout');

	// CRUD
	$this->get('/CRUD/{table}', 'It_All\BoutiqueCommerce\Controllers\CrudController:index');
	$this->get('/CRUD/{table}/insert', 'It_All\BoutiqueCommerce\Controllers\CrudController:getInsert')->setName('crud.getInsert');
	$this->get('/CRUD/{table}/{id}', 'It_All\BoutiqueCommerce\Controllers\CrudController:show');
	$this->post('/CRUD/{table}', 'It_All\BoutiqueCommerce\Controllers\CrudController:postInsert')->setName('crud.postInsert');
})->add(new AuthMiddleware($container));
