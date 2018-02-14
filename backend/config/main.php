<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'on beforeAction' => function ($event) {
         $session = Yii::$app->session;
           if (Yii::$app->controller->id != 'site' && Yii::$app->controller->action->id != 'login') {
            if (!$session['UserID']) {
                Yii::$app->user->logout(); 
            }        
        }
       },
    'modules' => [
      
        'mail' => [
            'class' => 'backend\modules\mail\mail',
        ],
        'report' => [
            'class' => 'backend\modules\report\report',
        ],
        'dailyreport' => [
            'class' => 'backend\modules\dailyreport\dailyreport',
        ],
        
         'dashboard' => [
            'class' => 'backend\modules\dashboard\dashboard',
        ],
        
         'user' => [
            'class' => 'backend\modules\user\user',
        ],

        'dailyreport' => [
            'class' => 'backend\modules\dailyreport\dailyreport',
        ],
        
        'stock' => [
            'class' => 'backend\modules\stock\stock',
        ],
		
		'holiday' => [
            'class' => 'backend\modules\holiday\holiday',
        ],
		
		'attendance' => [
            'class' => 'backend\modules\attendance\attendance',
        ],

        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],

        'leave' => [
            'class' => 'backend\modules\leave\leave',
        ],

        'dashboard' => [
            'class' => 'backend\modules\dashboard\dashboard',
        ],
        
        'payroll' => [
            'class' => 'backend\modules\payroll\payroll',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfValidation'=>false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'authTimeout' => 30,
        ],
        'session' => [
        'class' => 'yii\web\Session',
        'cookieParams' => ['httponly' => true, 'lifetime' => 3600 * 4],
        'timeout' =>30, //session expire
        'useCookies' => true,
    ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
//         'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//               'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'localhost',
//                'username' => 'username',
//                'password' => 'password',
//                'port' => '25',
//                'encryption' => 'tls',
//            ]
//             //'useFileTransport'=>false,
//        ],
       
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login'=>'site/login',
            ],
        ],
       
    ],
    'params' => $params,
];
