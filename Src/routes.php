<?php
declare(strict_types=1);

use It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authentication\AuthenticationMiddleware;
use It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authentication\GuestMiddleware;
use It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authorization\AuthorizationMiddleware;

// For maximum performance, routes should not be grouped
// https://github.com/slimphp/Slim/issues/2165

// use as shortcuts for callables in routes
$securityNs = 'It_All\BoutiqueCommerce\Src\Infrastructure\Security';
$domainNs = 'It_All\BoutiqueCommerce\Src\Domain';

/////////////////////////////////////////
// Routes that anyone can access

$slim->get('/',
    'It_All\BoutiqueCommerce\Src\Domain\Home\HomeView:index')
    ->setName('home');
/////////////////////////////////////////

/////////////////////////////////////////
// Routes that only non-authenticated users (Guests) can access

$slim->get('/' . $config['dirs']['admin'],
    $securityNs.'\Authentication\AuthenticationView:getLogin')
    ->add(new GuestMiddleware($container))
    ->setName('authentication.login');

$slim->post('/' . $config['dirs']['admin'],
    $securityNs.'\Authentication\AuthenticationController:postLogin')
    ->add(new GuestMiddleware($container))
    ->setName('authentication.post.login');

/////////////////////////////////////////

/////////////////////////////////////////
// Routes that only authenticated users access
// Note, if route needs authorization as well, the authorization is added prior to authentication, so that authentication is performed first

$slim->get('/' . $config['dirs']['admin'] . '/home',
    $domainNs.'\AdminHome\AdminHomeView:index')
    ->add(new AuthenticationMiddleware($container))
    ->setName('admin.home');

$slim->get('/' . $config['dirs']['admin'] . '/logout',
    $securityNs.'\Authentication\AuthenticationView:getLogout')
    ->add(new AuthenticationMiddleware($container))
    ->setName('authentication.logout');

// admins
$slim->get('/' . $config['dirs']['admin'] . '/admins',
    $domainNs.'\Admins\AdminsView:index')
    ->add(new AuthorizationMiddleware($container, $config['adminMinimumPermissions']['admins.index']))
    ->add(new AuthenticationMiddleware($container))
    ->setName('admins.index');

$slim->get('/' . $config['dirs']['admin'] . '/admins/insert',
    $domainNs.'\Admins\AdminsView:getInsert')
    ->add(new AuthorizationMiddleware($container, $config['adminMinimumPermissions']['admins.insert']))
    ->add(new AuthenticationMiddleware($container))
    ->setName('admins.insert');

$slim->post('/' . $config['dirs']['admin'] . '/admins/insert',
    $domainNs.'\Admins\AdminsController:postInsert')
    ->add(new AuthorizationMiddleware($container, $config['adminMinimumPermissions']['admins.insert']))
    ->add(new AuthenticationMiddleware($container))
    ->setName('admins.post.insert');

$slim->get('/' . $config['dirs']['admin'] . '/admins/{primaryKey}',
    $domainNs.'\Admins\AdminsView:getUpdate')
    ->add(new AuthorizationMiddleware($container, $config['adminMinimumPermissions']['admins.update']))
    ->add(new AuthenticationMiddleware($container))
    ->setName('admins.update');

$slim->post('/' . $config['dirs']['admin'] . '/admins/{primaryKey}',
    $domainNs.'\Admins\AdminsController:postUpdate')
    ->add(new AuthorizationMiddleware($container, $config['adminMinimumPermissions']['admins.update']))
    ->add(new AuthenticationMiddleware($container))
    ->setName('admins.post.update');
