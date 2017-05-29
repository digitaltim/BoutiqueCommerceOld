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
    ->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationMiddleware($container))
    ->setName('authentication.logout');

// admins
$slim->get('/' . $config['dirs']['admin'] . '/admins',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsView:show')
    ->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationMiddleware($container))
    ->setName('admins.show');

$slim->get('/' . $config['dirs']['admin'] . '/admins/insert',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsView:getInsert')
    ->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationMiddleware($container))
    ->setName('admins.insert');

$slim->post('/' . $config['dirs']['admin'] . '/admins/insert',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsController:postInsert')
    ->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationMiddleware($container))
    ->setName('admins.post.insert');

$slim->get('/' . $config['dirs']['admin'] . '/admins/{primaryKey}',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsView:getUpdate')
    ->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationMiddleware($container))
    ->setName('admins.update');

$slim->post('/' . $config['dirs']['admin'] . '/admins/{primaryKey}',
    'It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsController:postUpdate')
    ->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Authentication\AuthenticationMiddleware($container))
    ->setName('admins.post.update');

/////////////////////////////////////////

//// MVC Test
//$slim->get('/{table}',
//    'It_All\BoutiqueCommerce\Src\Infrastructure\ListView:output')
//    ->setName('table.show');
