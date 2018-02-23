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
    ],
];
