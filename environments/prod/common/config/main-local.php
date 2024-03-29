<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=thewhitebook',
            'username' => 'whitebook',
            'password' => 'DD9neHwJTmWhS8UL',
            'charset' => 'utf8',
            'tablePrefix' => 'whitebook_',
        ],
        'session' => [ //Use Redis Database for Session Storage
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'cache' => [ //Use Redis Database for Cache
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
            ]
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'https://thewhitebook.com.kw',
        ],
        'urlManagerVendor' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'https://vendor.thewhitebook.com.kw',
        ],
        'urlManagerAdmin' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'https://admin.thewhitebook.com.kw',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'notamedia\sentry\SentryTarget',
                    'dsn' => 'https://12f8992cb2f64b0a8f5c5e094c648a38:93850c4478f24f888b47203fd7928b5d@sentry.io/145337',
                    'levels' => ['error', 'warning'],
                    'context' => true, // Write the context information. The default is true.
                    'extraCallback' => function ($context, $extra) {
                        // some manipulation with data
                        if(!Yii::$app->user->isGuest){
                            if(isset(Yii::$app->user->identity->customer_email)){
                                $extra['customer_name'] = Yii::$app->user->identity->customer_name;
                                $extra['customer_email'] = Yii::$app->user->identity->customer_email;
                            }
                        }

                        return $extra;
                    }
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'htmlLayout' => 'layouts/whitebook-html',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sendgrid.net',
                'username' => 'whitebook',
                'password' => 'twbsmtpaccesspassword12',
                'port' => '587',
                'encryption' => 'tls',
                'plugins' => [
                    [
                        'class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin',
                    ],
                ],
            ],
        ],
    ],
];
