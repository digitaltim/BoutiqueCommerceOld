<?php
declare(strict_types=1);

$domainName = 'boutiquecommerce.org';

return [

    'storeName' => 'BoutiqueCommerce',

    'domainName' => $domainName,

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
        'fatalMessage' => 'Hamden, we have a problem.',
        'emailTo' => 'greg@it-all.com', // todo use generic email (or array) @ $domainName and test .env override
        'echoDev' => true,
        'emailDev' => false
    ]

];
