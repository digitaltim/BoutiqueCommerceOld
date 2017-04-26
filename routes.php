<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$slim->get('/', 'It_All\BoutiqueCommerce\Controllers\AdminController:index')->setName('home');

$slim->get('/' . $config['dirs']['admin'], 'It_All\BoutiqueCommerce\Controllers\AuthController:index')->setName('auth.signin');
$slim->post('/' . $config['dirs']['admin'], 'It_All\BoutiqueCommerce\Controllers\AuthController:post');

$slim->get('/' . $config['dirs']['admin'] . '/signup', 'It_All\BoutiqueCommerce\Controllers\AuthController:getSignup')->setName('auth.signup');
$slim->post('/' . $config['dirs']['admin'] . '/signup', 'It_All\BoutiqueCommerce\Controllers\AuthController:postSignup');

$slim->get('/CRUD/{table}', 'It_All\BoutiqueCommerce\Controllers\CrudController:index');
$slim->get('/CRUD/{table}/{id}', 'It_All\BoutiqueCommerce\Controllers\CrudController:show');
