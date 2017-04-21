<?php
// DIC configuration

$container = $slim->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Database
$container['db'] = function($c) {
    $db = new It_All\ServicePg\Postgres();
    return $db;
};

// PHPRenderer
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    return new \Slim\Views\PhpRenderer($settings['view']['template_path']);
};

// Mailer
$container['mailer'] = function($c) {
    $settings = $c->get('settings');
    return $settings['mailer'];
};

// Logger
$container['logger'] = function($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler($settings['pathLog']);
    $logger->pushHandler($file_handler);
    return $logger;
};

// Error Handling
unset($container['errorHandler']);
unset($container['phpErrorHandler']);

// -----------------------------------------------------------------------------
// Controller factories / registration
// -----------------------------------------------------------------------------
$container['HomeController'] = function ($c) {
    return new It_All\BoutiqueCommerce\Controllers\HomeController($c);
};
