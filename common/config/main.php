<?php

return [
    'name' => 'The White Book',
    'language' => 'en',
    'sourceLanguage' => 'en',
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'components' => [
        'resourceManager' => [
            'class' => 'common\components\S3ResourceManager',
            'key' => 'AKIAIQ4T42MGVRUZSFCQ',
            'secret' => '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',
            'bucket' => 'thewhitebook'
            /**
             * You can access the bucket with:
             * https://thewhitebook.s3.amazonaws.com/
             * https://thewhitebook.s3.amazonaws.com/folderName/fileName.jpg
             */
        ],
        'slack' => [
            'class' => 'understeam\slack\Client',
            'url' => 'https://hooks.slack.com/services/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',
            'username' => 'TWB',
        ],
        'httpclient' => [
            'class' => 'yii\httpclient\Client',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['backend\*', 'admin\*', 'frontend\*', 'common\*'],
                ],
                [
                    'class' => 'common\components\SlackLogger',
                    'logVars' => [],
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['backend\*', 'admin\*', 'frontend\*', 'common\*'],
                ],
            ],
        ],
        'urlManager' => [
          'class' => 'yii\web\UrlManager',
        ],
        'formatter' => [
            'currencyCode' => 'KWD',
            'defaultTimeZone' => 'Asia/Kuwait',
        ],
        'assetManager' => [
            //Link assets -> create symbolic links to assets
            'linkAssets' => true,

            //append time stamps to assets for cache busting
            //'appendTimestamp' => true,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
            ],
        ],
    ],
     //'timeZone' => 'Asia/Calcutta',
  ];
