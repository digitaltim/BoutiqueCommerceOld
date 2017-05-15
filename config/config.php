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

    'sessionTtlHours' => 24,

    'storage' => [
        'logs' => [
            'pathPhpErrors' => APP_ROOT . 'storage/logs/phpErrors.log',
            'pathEvents' => APP_ROOT . 'storage/logs/events.log'
        ],

        'pathCache' => APP_ROOT . 'storage/cache/'
    ],

    'pathTemplates' => APP_ROOT . 'ui/views/',

    'errors' => [
        'emailTo' => ['owner', 'programmer'], // emails must be set in 'emails' section
        'fatalMessage' => 'Apologies, there has been an error on our site. We have been alerted and will correct it as soon as possible.',
        'echoDev' => true, // echo on dev servers
        'emailDev' => false // email on dev servers (note live server will always email)
    ],

    'emails' => [
        'owner' => "owner@domainName",
        'programmer' => "programmer@domainName",
        'service' => "service@domainName"
    ]


];
