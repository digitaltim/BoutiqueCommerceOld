<?php
$displayErrorDetails = ($config['env'] == 'live' || !$config['debug']) ? false : true;
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

        // PhpRenderer settings
        'view' => [
            'template_path' => __DIR__ . '/../../ui/views/',
        ],

        'pathLog' => $config['logs']['pathEvents'],

    ]
];
