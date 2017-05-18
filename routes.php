<?php
declare(strict_types=1);

use It_All\BoutiqueCommerce\Middleware\AuthMiddleware;
use It_All\BoutiqueCommerce\Middleware\GuestMiddleware;

// For maximum performance, routes should not be grouped
// https://github.com/slimphp/Slim/issues/2165

/////////////////////////////////////////
// Routes that anyone can access

$slim->get('/',
    'It_All\BoutiqueCommerce\Controllers\HomeController:index')
    ->setName('home');
/////////////////////////////////////////

/////////////////////////////////////////
// Routes that only non-authenticated users (Guests) can access

$slim->get('/' . $config['dirs']['admin'],
    'It_All\BoutiqueCommerce\UI\Views\AuthenticationView:getSignIn')
    ->add(new GuestMiddleware($container))
    ->setName('auth.signin');

$slim->post('/' . $config['dirs']['admin'],
    'It_All\BoutiqueCommerce\Controllers\AuthController:postSignIn')
    ->add(new GuestMiddleware($container));

$slim->get('/' . $config['dirs']['admin'] . '/signup',
    'It_All\BoutiqueCommerce\UI\Views\AuthenticationView:getSignUp')
    ->add(new GuestMiddleware($container))
    ->setName('auth.signup');

$slim->post('/' . $config['dirs']['admin'] . '/signup',
    'It_All\BoutiqueCommerce\Controllers\AuthController:postSignUp')
    ->add(new GuestMiddleware($container));

/////////////////////////////////////////

/////////////////////////////////////////
// Routes that only authenticated users access
$slim->get('/' . $config['dirs']['admin'] . '/signout',
    'It_All\BoutiqueCommerce\UI\Views\AuthenticationView:getSignOut')
    ->setName('auth.signout');

// CRUD
$slim->get('/CRUD/{table}',
    'It_All\BoutiqueCommerce\UI\Views\Admin\CRUD\CrudView:index')
    ->add(new AuthMiddleware($container))
    ->setName('crud.show');

$slim->get('/CRUD/{table}/insert',
    'It_All\BoutiqueCommerce\UI\Views\Admin\CRUD\CrudView:getInsert')
    ->add(new AuthMiddleware($container))
    ->setName('crud.getInsert');

$slim->post('/CRUD/{table}/insert',
    'It_All\BoutiqueCommerce\Controllers\CrudController:postInsert')
    ->add(new AuthMiddleware($container))
    ->setName('crud.postInsert');

$slim->get('/CRUD/{table}/{primaryKey}',
    'It_All\BoutiqueCommerce\UI\Views\Admin\CRUD\CrudView:getUpdate')
    ->add(new AuthMiddleware($container))
    ->setName('crud.getUpdate');

$slim->post('/CRUD/{table}/{primaryKey}',
    'It_All\BoutiqueCommerce\Controllers\CrudController:postUpdate')
    ->add(new AuthMiddleware($container))
    ->setName('crud.postUpdate');

$slim->get('/CRUD/{table}/delete/{primaryKey}',
    'It_All\BoutiqueCommerce\Controllers\CrudController:delete')
    ->add(new AuthMiddleware($container))
    ->setName('crud.delete');
/////////////////////////////////////////

// MVC Test
$slim->get('/{table}',
    'It_All\BoutiqueCommerce\UI\Views\ListView:output')
    ->setName('table.show');
