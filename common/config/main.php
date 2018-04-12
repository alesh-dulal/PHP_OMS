<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'email' => [
            'class' => 'common\components\MailHelper',
        ],
        'crypto' => [
            'class' => 'common\components\EncryptionHelper',
        ],
        'empList' => [
            'class' => 'common\components\EmployeeListHelper',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.zoho.com',
                'username' => 'info@topnepal.com.np',
                'password' => 'TopNep#452',
                'port' => '465',
                'encryption' => 'ssl',
            ]
        ],
    ],
];
