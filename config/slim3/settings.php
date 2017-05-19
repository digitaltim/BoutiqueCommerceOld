<?php
declare(strict_types=1);

return [
    'settings' => [
        // Slim Settings
        'outputBuffering' => false, // just to uncomplicate things for now.

        'addContentLengthHeader' => false, // if this is not disabled, slim/App.php line 585 triggered an exception related to error handling, when the php set_error_handler() function was triggered

        // Twig Settings
        'view' => [
            'pathTemplates' => $config['pathTemplates'],
            'pathCache' => $config['storage']['pathCache'].'twig/',
            'autoReload' => $config['twigAutoReload'],
            'debug' => true
        ],

        // phpMailer Settings
        'mailer' => $mailer,

        // Storage Settings
        'storage' => [
            'pathLogs' => $config['storage']['logs']['pathEvents'],
            'pathTwigCache' => $config['storage']['pathCache'].'twig/',
        ],
        'dirs' => [
            'admin' => $config['dirs']['admin']
        ],

        // General settings
        'isLive' => $config['isLive'],
    ]
];
