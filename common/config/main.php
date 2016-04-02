<?php

return [
    'name' => 'The White Book',
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['backend\*', 'frontend\*', 'common\*'],
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
