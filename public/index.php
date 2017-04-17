<?php
require __DIR__ . '/../init.php';

// Instantiate Slim PHP
$settings = require __DIR__ . '/../config/slim3/settings.php';
$slim = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../config/slim3/dependencies.php';

// Register routes
require __DIR__ . '/../routes.php';

$container->logger->addInfo('My logger is now ready');

$slim->run();
