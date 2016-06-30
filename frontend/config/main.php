<?php

$params = array_merge(
    require(__DIR__.'/../../common/config/params.php'),
    require(__DIR__.'/../../common/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'The Whitebook',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\Customer',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'name' => 'app-frontend',
        ],
        // Override the urlManager component for i18n plugin
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',

            //Ignore adding /ar/ to the following url patterns
            'ignoreLanguageUrlPatterns' => [
                //'#^site/(login|register)#' => '#^(login|register)#',
                //'#^api/#' => '#^api/#',
                //'#^assets/#' => '#^assets/#',
            ],

            // List all supported languages here
            // Make sure, you include your app's default language.
            'languages' => ['en', 'ar'],
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
