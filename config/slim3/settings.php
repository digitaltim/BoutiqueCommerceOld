<?php
return [
    'settings' => [
        // Slim Settings
        'displayErrorDetails' => true,

        // Database Settings
        'db' => [
            'database' => $config['dbSettings']['database'],
            'username' => $config['dbSettings']['username'],
            'password' => $config['dbSettings']['password'],
            'host' => $config['dbSettings']['host'],
        ],

        // PhpRenderer settings
        'view' => [
            'template_path' => __DIR__ . '/../../ui/views/',
        ],
    ]
];
