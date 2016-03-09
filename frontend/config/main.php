<?php

$params = array_merge(
    require(__DIR__.'/../../common/config/params.php'),
    require(__DIR__.'/../../common/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

$k = [
    'id' => 'app-frontend',
    'name' => 'The Whitebook',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '/frontend/web',
    'components' => [
        'request' => [
            'baseUrl' => '/',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
        ],
        'cache' => [
                    'class' => 'yii\caching\FileCache',
         ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'newcomponent' => [
            'class' => 'backend\components\MainComponent',
        ],
        'common' => [
            'class' => 'common\components\Common',
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
            'errorAction' => 'default/error',
        ],
        
    ],
    'params' => $params,
];

return $k;
