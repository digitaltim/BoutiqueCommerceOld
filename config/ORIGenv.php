<?php
// note if env set to 'live', debug info will never be displayed even if debug set true
return [
    'env' => 'dev',
    'debug' => true,
    'database' => [
        'name' => 'btqcm',
        'username' => 'btqcm',
        'password' => 'woolsocks',
        'host' => '127.0.0.1',
        'port' => '5432'
    ],
    'phpmailer' => [
        'protocol' => 'smtp',
        'smtpHost' => 'relay.pair.com',
        'smtpPort' => 2525
    ],
    'errors' => [
        'reportMethods' => ['echo', 'log', 'email']
    ],
    'dirs' => [
        'admin' => 'private'
    ],
    'sendNotificationEmails' => true, // set false on dev only to stop receiving emails. forced true on live site.
];
