<?php
declare(strict_types=1);

// DIC configuration

// -----------------------------------------------------------------------------
// Services (Dependencies)
// -----------------------------------------------------------------------------

// Create initial connection to DB
$db = new \It_All\BoutiqueCommerce\Src\Infrastructure\Database\Postgres(
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
$container['authentication'] = function($container) {
    return new It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authentication\AuthenticationService;
};

// Authorization
$container['authorization'] = function($container) {
    $settings = $container->get('settings');
    return new It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authorization\AuthorizationService($settings['authorization']);
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
    $view->getEnvironment()->addGlobal('authentication', [
        'check' => $container->authentication->check(),
        'user' => $container->authentication->user()
    ]);

    if (isset($_SESSION['adminNotice'])) {
        $view->getEnvironment()->addGlobal('adminNotice', $_SESSION['adminNotice']);
    }

    // make some config setting available inside templates
    $view->getEnvironment()->addGlobal('isLive', $settings['isLive']);
    $view->getEnvironment()->addGlobal('storeName', $settings['storeName']);

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
    return new \It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService();
};

// CSRF
$container['csrf'] = function ($container) {
    $storage = null; // cannot directly pass null because received by reference.
    // setting the persistentTokenMode parameter true allows redisplaying a form with errors with a render rather than redirect call and will not cause CSRF failure if the page is refreshed (http://blog.ircmaxell.com/2013/02/preventing-csrf-attacks.html)
    return new \Slim\Csrf\Guard('csrf', $storage, null, 200, 16, true);
};

// End Services (Dependencies)

// Error Handling - remove Slim's Error Handling
unset($container['errorHandler']);
unset($container['phpErrorHandler']);

// -----------------------------------------------------------------------------
// Middleware registration
// -----------------------------------------------------------------------------
$slim->add(new \It_All\BoutiqueCommerce\Src\Infrastructure\Security\CsrfViewMiddleware($container));
$slim->add($container->csrf);
