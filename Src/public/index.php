<?php
declare(strict_types=1);

require __DIR__ . '/../init.php';

// Instantiate Slim PHP
$settings = require APP_ROOT . 'config/slim3/settings.php';
$slim = new \Slim\App($settings);

$container = $slim->getContainer();

// Set up Slim dependencies
require APP_ROOT . 'config/slim3/dependencies.php';

// Error Handling - remove Slim's Error Handling
unset($container['errorHandler']);
unset($container['phpErrorHandler']);

// Middleware registration
$slim->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Security\CsrfViewMiddleware($container));
$slim->add($container->csrf);

// Register routes
require APP_ROOT . 'routes.php';

$slim->run();
