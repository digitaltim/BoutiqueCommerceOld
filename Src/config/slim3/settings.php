<?php
declare(strict_types=1);

return [
    'settings' => [

        // Slim Settings
        'outputBuffering' => false, // just to uncomplicate things for now.

        'addContentLengthHeader' => false, // if this is not disabled, slim/App.php line 585 triggered an exception related to error handling, when the php set_error_handler() function was triggered

        'routerCacheFile' => $config['storage']['pathCache'].'router.txt',

        'authentication' => $config['maxFailedLogins'],

        'authorization' => $config['adminMinimumPermissions'],

        // Twig Settings
        'view' => [
            'pathTemplates' => $config['pathTemplates'],
            'pathCache' => $config['storage']['pathCache'].'twig/',
            'autoReload' => $config['twigAutoReload'],
            'debug' => true
        ],

        // phpMailer Settings
        'mailer' => $mailer,

        'storage' => [
            'pathLogs' => $config['storage']['logs']['pathEvents'],
            'pathTwigCache' => $config['storage']['pathCache'].'twig/',
        ],

        'dirs' => [
            'admin' => $config['dirs']['admin']
        ],

        // General settings
        'isLive' => $config['isLive'],
        'businessName' => $config['businessName'],
        'emails' => $config['emails']
    ],

    //Override the default Not Found Handler
    'notFoundHandler' => function ($container) {
        return function ($request, $response) use ($container) {
            return $container['response']
                ->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withRedirect($container->router->pathFor('pageNotFound'));
        };
    }
];
