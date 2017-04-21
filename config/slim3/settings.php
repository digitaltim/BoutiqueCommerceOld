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
            'port' => $config['database']['port'],
        ],

        // PhpRenderer Settings
        'view' => [
            'template_path' => __DIR__ . '/../../ui/views/',
        ],

        // phpMailer Settings
        'mailer' => [
            'defaultFromEmail' => $config['storeEmails']['defaultFromEmail'],
            'defaultFromName' => $config['storeEmails']['defaultFromName'],
            'protocol' => $config['phpmailer']['protocol'],
            'smtpHost' => $config['phpmailer']['smtpHost'],
            'smtpPort' => $config['phpmailer']['smtpPort']
        ],

        'pathLog' => $config['logs']['pathEvents'],

    ]
];
