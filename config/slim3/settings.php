<?php
return [
    'settings' => [
        // Slim Settings
        'displayErrorDetails' => true,

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

        'pathLog' => APP_ROOT . '/storage/logs/events.log',

    ]
];
