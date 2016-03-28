<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'components' => [
        'urlManager' => [
          'class' => 'yii\web\UrlManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
          'translations' => [
              'frontend*' => [
                  'class' => 'yii\i18n\PhpMessageSource',
                  'basePath' => '@common/messages',
              ],
              'backend*' => [
                  'class' => 'yii\i18n\PhpMessageSource',
                  'basePath' => '@common/messages',
              ],
          ],
        ],
    ],
    'modules' => [
       'admin' => [
           'class' => 'backend\modules\admin\Module',
       ],
        'vendor' => [
           'class' => 'backend\modules\vendor\Module',
       ],
    ],
];
