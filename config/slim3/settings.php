<?php
declare(strict_types=1);

return [
    'settings' => [
        // Slim Settings
        'displayErrorDetails' => !$config['isLive'], // slim error handling currently disabled so this has no effect

        'outputBuffering' => false, // just to uncomplicate things for now.

        'addContentLengthHeader' => false, // if this is not disabled, slim/App.php line 585 triggered an exception related to error handling, when the php set_error_handler() function was triggered

        // Database Settings
        'db' => [
            'database' => $config['database']['name'],
            'username' => $config['database']['username'],
            'password' => $config['database']['password'],
            'host' => $config['database']['host'],
            'port' => $config['database']['port'],
        ],

        // Twig Settings
        'view' => [
            'pathTemplates' => $config['pathTemplates'],
            'pathCache' => $config['storage']['pathCache'].'twig/',
            'autoReload' => !$config['isLive']
        ],

        // phpMailer Settings
        'mailer' => $mailer,

        // Storage Settings
        'storage' => [
            'pathLogs' => $config['storage']['logs']['pathEvents'],
            'pathTwigCache' => $config['storage']['pathCache'].'twig/',
        ]

    ]
];
