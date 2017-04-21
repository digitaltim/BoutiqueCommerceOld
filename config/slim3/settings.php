<?php
$displayErrorDetails = ($config['env'] == 'live') ? false : true;
$twigAutoReload = ($config['env'] == 'live') ? false : true;
return [
    'settings' => [
        // Slim Settings
        'displayErrorDetails' => $displayErrorDetails, // slim error handling currently disabled so this has no effect

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
            'autoReload' => $twigAutoReload
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
