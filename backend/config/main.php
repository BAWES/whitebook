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
        'user' => [
            'identityClass' => 'common\models\Vendor',
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
    'params' => $params,

];
