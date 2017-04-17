<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Database
$container['db'] = function($c) {
    $settings = $c->get('settings');
    $db = new It_All\ServicePg\Postgres();
    $db->connect($settings['db']['database'], $settings['db']['username'], $settings['db']['password']);
    return $db;
};

// PHPRenderer
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    return new \Slim\Views\PhpRenderer($settings['view']['template_path']);
};

// Logger
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../storage/logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

// -----------------------------------------------------------------------------
// Controller factories / registration
// -----------------------------------------------------------------------------
$container['HomeController'] = function ($c) {
    return new It_All\BoutiqueCommerce\Controllers\HomeController($c->get('db'), $c->get('view'));
};
