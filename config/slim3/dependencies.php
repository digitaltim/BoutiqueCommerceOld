<?php
// DIC configuration

$container = $slim->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Database
$container['db'] = function($c) {
    $settings = $c->get('settings');
    $db = new It_All\ServicePg\Postgres();
    try {
        $db->connect($settings['db']['database'], $settings['db']['username'], $settings['db']['password']);
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    return $db;
};

// PHPRenderer
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    return new \Slim\Views\PhpRenderer($settings['view']['template_path']);
};

// Logger
$container['logger'] = function($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger('my_logger');
    //$file_handler = new \Monolog\Handler\StreamHandler("../storage/logs/app.log");
    $file_handler = new \Monolog\Handler\StreamHandler($settings['pathLog']);
    $logger->pushHandler($file_handler);
    return $logger;
};

// -----------------------------------------------------------------------------
// Controller factories / registration
// -----------------------------------------------------------------------------
$container['HomeController'] = function ($c) {
    return new It_All\BoutiqueCommerce\Controllers\HomeController($c->get('db'), $c->get('view'));
};
