<?php
declare(strict_types=1);

$domainName = 'boutiquecommerce.org';

return [

    'storeName' => 'BoutiqueCommerce',

    'domainName' => $domainName,

    'projectsUrl' => 'https://www.' . $domainName . '/redmine/',

    'domainUseWww' => false,

    'storeEmails' => [
        'defaultFromEmail' => 'service@'.$domainName,
        'defaultFromName' => 'Boutique Commerce'
    ],

    'session' => [
        'ttlHours' => 24,
        'savePath' => APP_ROOT . '/../storage/sessions',
    ],

    'storage' => [
        'logs' => [
            'pathPhpErrors' => APP_ROOT . '/../storage/logs/phpErrors.log',
            'pathEvents' => APP_ROOT . '/../storage/logs/events.log'
        ],

        'pathCache' => APP_ROOT . '/../storage/cache/'
    ],

    'pathTemplates' => APP_ROOT . 'templates/',

    'errors' => [
        'emailTo' => ['owner', 'programmer'], // emails must be set in 'emails' section
        'fatalMessage' => 'Apologies, there has been an error on our site. We have been alerted and will correct it as soon as possible.',
        'echoDev' => true, // echo on dev servers (note, live server will never echo)
        'emailDev' => false // email on dev servers (note, live server will always email)
    ],

    'emails' => [
        'owner' => "owner@domainName",
        'programmer' => "programmer@domainName",
        'service' => "service@domainName"
    ]
];
