<?php
// installation instructions: copy this to env.php and update as your environment dictates
// note if env set to 'live', debug info will never be displayed even if debug set true
return [
    'env' => 'dev',
    'debug' => true,
    'database' => [
        'name' => 'btqcm',
        'username' => 'btqcm',
        'password' => 'woolsocks',
        'host' => '127.0.0.1'
    ],
    'phpmailer' => [
        'protocol' => 'smtp',
        'smtpHost' => '?.?.com',
        'smtpPort' => 2525
        // todo may need u/p vars here
    ],
    'errors' => [
        'reportMethods' => ['echo', 'log', 'email'] // see ErrorHandler for rules
    ],
    'dirs' => [
        'admin' => 'private'
    ],
    'sendNotificationEmails' => true, // set false on dev only to stop receiving emails. forced true on live site.
];
