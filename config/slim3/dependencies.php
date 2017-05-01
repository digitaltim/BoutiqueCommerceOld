<?php
declare(strict_types=1);

// DIC configuration

$container = $slim->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Database
$container['db'] = function($container) {
    $settings = $container->get('settings');
    
    $db = new \It_All\BoutiqueCommerce\Postgres(
        $settings['db']['database'],
        $settings['db']['username'],
        $settings['db']['password'],
        $settings['db']['host'],
        $settings['db']['port']
    );
    
    return $db;
};

// Authentication
$container['auth'] = function($container) {
    return new It_All\BoutiqueCommerce\Auth\Auth;
};

// Twig
$container['view'] = function ($container) {
    $settings = $container->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['pathTemplates'], [
        'cache' => $settings['view']['pathCache'],
        'auto_reload' => $settings['view']['autoReload']
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->router, $basePath));
    
    // make auth class available inside templates
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user()
    ]);

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

// Form Validation
$container['validator'] = function ($container) {
    return new It_All\BoutiqueCommerce\Validation\Validator;
};

// Error Handling
unset($container['errorHandler']);
unset($container['phpErrorHandler']);

// -----------------------------------------------------------------------------
// Controller factories / registration
// -----------------------------------------------------------------------------
$container['HomeController'] = function ($container) {
    return new It_All\BoutiqueCommerce\Controllers\HomeController($container);
};

$container['AdminController'] = function ($container) {
    return new It_All\BoutiqueCommerce\Controllers\AdminController($container);
};

$container['AuthController'] = function ($container) {
    return new It_All\BoutiqueCommerce\Controllers\AuthController($container);
};


$container['CrudController'] = function ($container) {
    return new It_All\BoutiqueCommerce\Controllers\CrudController($container);
};

// -----------------------------------------------------------------------------
// Middleware registration
// -----------------------------------------------------------------------------
$slim->add(new It_All\BoutiqueCommerce\Middleware\ValidationErrorsMiddleware($container));