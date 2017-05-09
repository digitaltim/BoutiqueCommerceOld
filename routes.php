<?php
declare(strict_types=1);

use It_All\BoutiqueCommerce\Middleware\AuthMiddleware;
use It_All\BoutiqueCommerce\Middleware\GuestMiddleware;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Routes that anyone can access
$slim->get('/', 'It_All\BoutiqueCommerce\Controllers\HomeController:index')->setName('home');

// Group routes that a guest user can access
$slim->group('', function () {
    $container = $this->getContainer();
    $settings = $container->get('settings');

    $this->get('/' . $settings['dirs']['admin'], 'It_All\BoutiqueCommerce\Controllers\AuthController:getSignIn')->setName('auth.signin');
    $this->post('/' . $settings['dirs']['admin'], 'It_All\BoutiqueCommerce\Controllers\AuthController:postSignIn');

    $this->get('/' . $settings['dirs']['admin'] . '/signup', 'It_All\BoutiqueCommerce\Controllers\AuthController:getSignUp')->setName('auth.signup');
    $this->post('/' . $settings['dirs']['admin'] . '/signup', 'It_All\BoutiqueCommerce\Controllers\AuthController:postSignUp');
})->add(new GuestMiddleware($container));

// Group routes that a user needs to be signed in to access
$slim->group('', function () {
    $container = $this->getContainer();
    $settings = $container->get('settings');

    $this->get('/' . $settings['dirs']['admin'] . '/signout', 'It_All\BoutiqueCommerce\Controllers\AuthController:getSignOut')->setName('auth.signout');

    // CRUD
    $this->get('/CRUD/{table}', 'It_All\BoutiqueCommerce\Controllers\CrudController:index')->setName('crud.show');
    $this->get('/CRUD/{table}/insert', 'It_All\BoutiqueCommerce\Controllers\CrudController:getInsert')->setName('crud.getInsert');
    $this->post('/CRUD/{table}', 'It_All\BoutiqueCommerce\Controllers\CrudController:postInsert')->setName('crud.postInsert');
    $this->get('/CRUD/{table}/{primaryKey}', 'It_All\BoutiqueCommerce\Controllers\CrudController:getUpdate')->setName('crud.getUpdate');
    $this->post('/CRUD/{table}/{primaryKey}', 'It_All\BoutiqueCommerce\Controllers\CrudController:postUpdate')->setName('crud.postUpdate');
    $this->get('/CRUD/{table}/delete/{primaryKey}', 'It_All\BoutiqueCommerce\Controllers\CrudController:delete')->setName('crud.delete');
})->add(new AuthMiddleware($container));

//$slim->get('/{table}', 'It_All\BoutiqueCommerce\UI\Views\ListView:output')->setName('table.show');

$slim->get('/{table}', function ($reqest, $response, $args) {
    $class = "It_All\\BoutiqueCommerce\\Models\\".ucwords($args['table']);
    $dbTableModel = new $class($this->db);
    $modelClass = "It_All\\BoutiqueCommerce\\Models\\Every".ucwords($args['table'])."List";
    $this->model = new $modelClass($dbTableModel);
    return $this->view->render($response, 'admin/list.twig', ['title' => $args['table'], 'results' => $this->model->getRecords()]);
});
