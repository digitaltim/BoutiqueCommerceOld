<?php
// DIC configuration

$container = $slim->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Database
$container['db'] = function($c) {
    $settings = $c->get('settings');
    
    $db = new It_All\ServicePg\Postgres(
        $settings['db']['database'],
        $settings['db']['username'],
        $settings['db']['password'],
        $settings['db']['host'],
        $settings['db']['port']
    );
    
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
    return new It_All\BoutiqueCommerce\Services\Mailer($settings['mailer']['defaultFromEmail'], $settings['mailer']['defaultFromName'], $settings['mailer']['protocol'], $settings['mailer']['smtpHost'], $settings['mailer']['smtpPort']);
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

// Error Handling
unset($container['errorHandler']);
unset($container['phpErrorHandler']);

// -----------------------------------------------------------------------------
// Controller factories / registration
// -----------------------------------------------------------------------------
$container['HomeController'] = function ($c) {
    return new It_All\BoutiqueCommerce\Controllers\HomeController($c);
};
