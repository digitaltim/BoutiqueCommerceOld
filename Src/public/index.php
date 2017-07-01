<?php
declare(strict_types=1);

require __DIR__ . '/../init.php';

// Instantiate Slim PHP
$settings = require APP_ROOT . 'config/slim3/settings.php';
$slim = new \Slim\App($settings);

$container = $slim->getContainer();

// Set up Slim dependencies
require APP_ROOT . 'config/slim3/dependencies.php';

// remove Slim's Error Handling
unset($container['errorHandler']);
unset($container['phpErrorHandler']);

// Middleware registration
// handle CSRF check failures and allow Twig to access and insert CSRF fields to forms
$slim->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Security\CsrfMiddleware($container));
// slim CSRF check middleware
$slim->add($container->csrf);

// Register routes
require APP_ROOT . 'routes.php';

$slim->run();
