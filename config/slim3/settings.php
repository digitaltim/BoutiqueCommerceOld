<?php
$displayErrorDetails = ($config['env'] == 'live') ? false : true;
return [
    'settings' => [
        // Slim Settings
        'displayErrorDetails' => $displayErrorDetails,

        // Database Settings
        'db' => [
            'database' => $config['database']['name'],
            'username' => $config['database']['username'],
            'password' => $config['database']['password'],
            'host' => $config['database']['host'],
        ],

        // PhpRenderer Settings
        'view' => [
            'template_path' => __DIR__ . '/../../ui/views/',
        ],

        // phpMailer Settings
        'mailer' => $mailer,

        'pathLog' => $config['logs']['pathEvents'],

    ]
];
