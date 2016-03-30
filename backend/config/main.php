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
    'bootstrap' => ['gii'],
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
        'gii' => [
          'class' => 'yii\gii\Module',
        ],
        'admin' => [
          'class' => 'backend\modules\admin\Module'
        ],
        'vendor' => [
          'class' => 'backend\modules\vendor\Module'
        ],
      ],
     'aliases' => [
        '@vendor_images' => '@app/web/uploads/vendor_images/',
        '@vendor_item_images_210' => '/backend/web/uploads/vendor_images/210x210/',
        //'@vendor_item_images_210' => '/backend/web/uploads/vendor_images/',
        '@sales_guide_images' => '@app/web/uploads/guide_images/',
        '@sub_category' => '@app/web/uploads/subcategory_icon/',
        '@vendor_image' => '/backend/web/uploads/vendor_images/',
        //'@vendor_image' => '/backend/web/uploads/vendor_images/',
        '@frontend_app_images' => '/frontend/web/images/',
        '@gif_img' => '/frontend/web/images/ajax-loader.gif',
        '@sub_category' => '@app/web/uploads/subcategory_icon/',
        '@top_category' => '@app/web/uploads/category_ads/top/',
        '@gif_img' => '/frontend/web/images/ajax-loader.gif',
        '@bottom_category' => '@app/web/uploads/category_ads/bottom/',
        '@home_ads' => '@app/web/uploads/home_ads/',
        ],
    'params' => $params,

];
