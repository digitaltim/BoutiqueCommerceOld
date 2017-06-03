<?php

return [
    'isLive' => false,

    'dirs' => [
        'admin' => 'private'
    ],

    'database' => [
        'name' => '',
        'username' => '',
        'password' => '',
        'host' => '127.0.0.1',
        'port' => 5432
    ],

    'phpmailer' => [
        'protocol' => 'smtp',
        'smtpHost' => '',
        'smtpPort' => 2525
    ],

    'emails' => [
        'owner' => '',
        'programmer' => ''
    ],

    'twigAutoReload' => true
];
