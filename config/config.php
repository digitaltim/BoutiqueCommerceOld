<?php

return [

    'storeName' => 'BoutiqueCommerce',

    'domainName' => 'boutiquecommerce.org',

    'domainUseWww' => false,

    'storeEmails' => [
        'defaultFromEmail' => 'service@boutiquecommerce.org',
        'defaultFromName' => 'Boutique Commerce'
    ],

    'sessionTtlHours' => 24,

    'logs' => [
        'pathPhpErrors' => APP_ROOT . 'storage/logs/phpErrors.log',
        'pathEvents' => APP_ROOT . 'storage/logs/events.log'
    ]

];
