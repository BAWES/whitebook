<?php

return [
    'name' => 'The White Book',
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'components' => [
        'resourceManager' => [
            'class' => 'common\components\S3ResourceManager',
            'key' => 'AKIAJJCSTGJVP4LXDOJQ',
            'secret' => '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',
            'bucket' => 'whitebook-files'
            /**
             * You can access the bucket with:
             * https://whitebook-files.s3.amazonaws.com/
             * https://whitebook-files.s3.amazonaws.com/folderName/fileName.jpg
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
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
              'frontend*' => [
                  'class' => 'yii\i18n\PhpMessageSource',
                  'basePath' => '@common/messages',
                  'sourceLanguage' => 'en',
              ],
              'backend*' => [
                  'class' => 'yii\i18n\PhpMessageSource',
                  'basePath' => '@common/messages',
                  'sourceLanguage' => 'en',
              ],
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
  ];
