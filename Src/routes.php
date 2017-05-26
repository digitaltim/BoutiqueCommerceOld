<?php
declare(strict_types=1);

use It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationMiddleware;
use It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\GuestMiddleware;

// For maximum performance, routes should not be grouped
// https://github.com/slimphp/Slim/issues/2165

/////////////////////////////////////////
// Routes that anyone can access

$slim->get('/',
    'It_All\BoutiqueCommerce\Src\Domain\Home\HomeView:index')
    ->setName('home');
/////////////////////////////////////////

/////////////////////////////////////////
// Routes that only non-authenticated users (Guests) can access

$slim->get('/' . $config['dirs']['admin'],
    'It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationView:getLogin')
    ->add(new GuestMiddleware($container))
    ->setName('authentication.login');

$slim->post('/' . $config['dirs']['admin'],
    'It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationController:postLogin')
    ->add(new GuestMiddleware($container))
    ->setName('authentication.post.login');

/////////////////////////////////////////

/////////////////////////////////////////
// Routes that only authenticated users access

$slim->get('/' . $config['dirs']['admin'] . '/logout',
    'It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationView:getLogout')
    ->setName('authentication.logout');

// admins
$slim->get('/' . $config['dirs']['admin'] . '/admins',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsView:show')
    ->setName('admins.show');

$slim->get('/' . $config['dirs']['admin'] . '/admins/insert',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsView:getInsert')
    ->setName('admins.insert');

$slim->post('/' . $config['dirs']['admin'] . '/admins/insert',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsController:postInsert')
    ->setName('admins.post.insert');

$slim->get('/' . $config['dirs']['admin'] . '/admins/{primaryKey}',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsView:getUpdate')
    ->setName('admins.update');

$slim->post('/' . $config['dirs']['admin'] . '/admins/{primaryKey}',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsController:postUpdate')
    ->setName('admins.post.update');

// CRUD
$slim->get('/CRUD/{table}',
    'It_All\BoutiqueCommerce\Src\Infrastructure\Crud\CrudView:index')
    ->add(new AuthenticationMiddleware($container))
    ->setName('crud.show');

$slim->get('/CRUD/{table}/insert',
    'It_All\BoutiqueCommerce\Src\Infrastructure\Crud\CrudView:getInsert')
    ->add(new AuthenticationMiddleware($container))
    ->setName('crud.getInsert');

$slim->post('/CRUD/{table}/insert',
    'It_All\BoutiqueCommerce\Src\Infrastructure\Crud\CrudController:postInsert')
    ->add(new AuthenticationMiddleware($container))
    ->setName('crud.postInsert');

$slim->get('/CRUD/{table}/{primaryKey}',
    'It_All\BoutiqueCommerce\Src\Infrastructure\Crud\CrudView:getUpdate')
    ->add(new AuthenticationMiddleware($container))
    ->setName('crud.getUpdate');

$slim->post('/CRUD/{table}/{primaryKey}',
    'It_All\BoutiqueCommerce\Src\Infrastructure\Crud\CrudController:postUpdate')
    ->add(new AuthenticationMiddleware($container))
    ->setName('crud.postUpdate');

$slim->get('/CRUD/{table}/delete/{primaryKey}',
    'It_All\BoutiqueCommerce\Src\Infrastructure\Crud\CrudController:delete')
    ->add(new AuthenticationMiddleware($container))
    ->setName('crud.delete');
/////////////////////////////////////////

// MVC Test
$slim->get('/{table}',
    'It_All\BoutiqueCommerce\Src\Infrastructure\ListView:output')
    ->setName('table.show');
