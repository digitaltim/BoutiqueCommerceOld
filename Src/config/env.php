<?php

return [
    'isLive' => false,

    'dirs' => [
        'admin' => 'private'
    ],

    'database' => [
        'name' => 'btqcm',
        'username' => 'btqcm',
        'password' => 'woolsocks',
        'host' => '127.0.0.1',
        'port' => 5432
    ],

    'phpmailer' => [
        'protocol' => 'smtp',
        'smtpHost' => 'relay.pair.com',
        'smtpPort' => 2525
    ],

    'emails' => [
        'owner' => 'greg@it-all.com',
        'programmer' => 'greg@helloboutique.com',
        'service' => 'greg@it-all.com'
    ],

    'twigAutoReload' => true
];
