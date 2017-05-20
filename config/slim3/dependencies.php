<?php
declare(strict_types=1);

use Respect\Validation\Validator as v;

// DIC configuration

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Create initial connection to DB
$db = new \It_All\BoutiqueCommerce\Postgres(
    $config['database']['name'],
    $config['database']['username'],
    $config['database']['password'],
    $config['database']['host'],
    $config['database']['port']
);

// Database
$container['db'] = function($container) use ($db) {
    return $db;
};

// Authentication
$container['auth'] = function($container) {
    return new It_All\BoutiqueCommerce\Auth\Auth;
};

// Flash messages
$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

// Form Former
$container['form'] = function ($container) {
    return new \It_All\FormFormer\Form();
};

// Twig
$container['view'] = function ($container) {
    $settings = $container->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['pathTemplates'], [
        'cache' => $settings['view']['pathCache'],
        'auto_reload' => $settings['view']['autoReload'],
        'debug' => $settings['view']['debug']
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->router, $basePath));
    if ($settings['view']['debug']) {
        // allows {{ dump(var) }}
        $view->addExtension(new Twig_Extension_Debug());
    }

    // make auth class available inside templates
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user()
    ]);

    // make flash messages available inside templates
    $view->getEnvironment()->addGlobal('flash', $container->flash);

    // make isLive setting available inside templates
    $view->getEnvironment()->addGlobal('isLive', $settings['isLive']);

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
    $logger = new \Monolog\Logger('monologger');
    $file_handler = new \Monolog\Handler\StreamHandler($settings['storage']['pathLogs']);
    $logger->pushHandler($file_handler);
    return $logger;
};

// Form Validation
$container['validator'] = function ($container) {
    return new \It_All\BoutiqueCommerce\Services\Validator();
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
    return new It_All\BoutiqueCommerce\Controllers\CrudController($container, 'test');
};

$container['ListView'] = function ($container) {
    return new It_All\BoutiqueCommerce\UI\Views\ListView($container, 'test');
};

// -----------------------------------------------------------------------------
// Csrf registration
// -----------------------------------------------------------------------------
$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard();
};

$slim->add($container->csrf);
