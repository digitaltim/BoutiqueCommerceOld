<?php
require __DIR__ . '/../vendor/autoload.php';

session_start();

$config = array_merge(require __DIR__ . '/../config/config.php', require __DIR__ . '/../config/env.php');

// Instantiate Slim PHP
$settings = require __DIR__ . '/../config/slim3/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../config/slim3/dependencies.php';

// Register routes
require __DIR__ . '/../routes.php';

$app->run();
