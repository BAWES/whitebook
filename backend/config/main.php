<?php

$params = array_merge(
    require(__DIR__.'/../../common/config/params.php'),
    require(__DIR__.'/../../common/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
      'request' => [
          'enableCsrfValidation' => false,
       ],
      'cache' => [
                  'class' => 'yii\caching\FileCache',
       ],
      'authManager' => [
           'class' => 'yii\rbac\DbManager',
           'defaultRoles' => ['admin', 'author'],
        ],
       'urlManager' => [
          'class' => 'yii\web\UrlManager',
          'baseUrl' => '/backend/web/',
          'showScriptName' => false,
          'enablePrettyUrl' => true,
          'rules' => [
                  'login' => 'site/login',
                  'password' => 'site/password',
                  'users' => 'users/index',
                  '<controller:\w+>/<id:\d+>' => '<controller>/view',
                  '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                  '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
              ],
        ],
        'themeURL' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/backend/web/themes/default',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => false,
        ],
        'session' => [
            'name' => 'app-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                 ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'newcomponent' => [
            'class' => 'backend\components\MainComponent',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        YII_ENV_DEV ? '' : '',
                     ],
                  ],
            ],
        ],
     ],
    'modules' => [
        'admin' => [
          'class' => 'backend\modules\admin\Module'
        ],
        'vendor' => [
          'class' => 'backend\modules\vendor\Module'
        ],
    ],
    'params' => $params,

];
