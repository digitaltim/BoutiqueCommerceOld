<?php
declare(strict_types=1);

return [
    'settings' => [

        // Slim Settings
        'outputBuffering' => false, // just to uncomplicate things for now.

        'addContentLengthHeader' => false, // if this is not disabled, slim/App.php line 585 triggered an exception related to error handling, when the php set_error_handler() function was triggered

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
        'storeName' => $config['storeName']
    ],

    //Override the default Not Found Handler
    'notFoundHandler' => function ($c) {
        return function ($request, $response) use ($c) {
            return $c['response']
                ->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withRedirect($c->router->pathFor('pageNotFound'));
        };
    }
];
