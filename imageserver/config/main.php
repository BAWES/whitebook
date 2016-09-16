<?php

$params = array_merge(
require(__DIR__.'/../../common/config/params.php'),
require(__DIR__.'/../../common/config/params-local.php'),
require(__DIR__.'/params.php'),
require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-imageserver',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => false,
        ],
        'session' => [
            'name' => 'app-imageserver',
        ],
        'log' => [
            'traceLevel' => 3,//YII_DEBUG ? 3 : 0,
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
    ],
    'params' => $params,

];
