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

//// PHPRenderer
//$container['view'] = function ($container) {
//    $settings = $container->get('settings');
//    return new \Slim\Views\PhpRenderer($settings['view']['template_path']);
//};

// Twig
$container['view'] = function ($container) {
    $settings = $container->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['pathTemplates'], [
        'cache' => $settings['view']['pathCache'],
        'auto_reload' => $settings['view']['autoReload']
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

// Mailer
$container['mailer'] = function($container) {
    $settings = $container->get('settings');
    return $settings['mailer'];
};

// Logger
$container['logger'] = function($container) {
    $settings = $container->get('settings');
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler($settings['storage']['pathLogs']);
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
