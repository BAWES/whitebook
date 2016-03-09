<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManager' => [
          'class' => 'yii\web\UrlManager',
          'baseUrl' => '/',
          'enablePrettyUrl' => true,
          'showScriptName' => false,

          'rules' => [
                //'defaultRoute' => '/default/index',

                //'/'=>'/default', /* Line important*/

                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<action:\w+>' => '<controller>/<action>',
                '<slug:[A-Za-z0-9\_-]+>' => 'default/cmspages',
                ],
        ],
    ],
];
