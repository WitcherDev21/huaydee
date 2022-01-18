<?php
$params = array_merge(require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php');

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@frontend/views/user',
                ]
            ]
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend'
        ],
        'user' => [
            // 'identityClass' => 'common\models\User',
            'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => [
                'name' => '_identity-frontend',
                'httpOnly' => true
            ],
            'authTimeout' => 7200,
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'writeCallback' => function ($session) {
                return [
                    'user_id' => Yii::$app->user->id,
                ];
            }
            // 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
            // 'sessionTable' => 'my_session', // session table name. Defaults to 'session'.
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning'
                    ]
                ]
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id>' => '<controller>/<action>',
                'post-credit-transection/status-bank/<status>' => 'post-credit-transection/status-bank',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'js/jquery.min.js'
                    ]
                ]
            ]
        ],
        'i18n' => [
            'translations' => [
                'user*' => [ //
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages', // my custom message path.
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'user' => 'app.php', // I put this file on folder common/messages/ms/user.php so yours zh-CN
                    ],
                ]
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'enableConfirmation' => false,
            'cost' => 12,
            'admins' => [
                'admin'
            ],
            'layout' => '@frontend/themes/login/views/layouts/main.php',
            'controllerMap' => [
                'registration' => 'frontend\controllers\user\RegistrationController',
                'security' => 'frontend\controllers\user\SecurityController',
                'profile' => 'frontend\controllers\user\ProfileController',
            ],
            'modelMap' => [
                'RegistrationForm' => 'frontend\models\RegistrationForm',
            ],

        ]
    ]
    // 'rbac' => 'dektrium\rbac\RbacWebModule',
    // 'admin' => [
    // 'class' => 'mdm\admin\Module',
    // 'layout'=>'left-menu'
    // ],
    ,
    'params' => $params,

];
