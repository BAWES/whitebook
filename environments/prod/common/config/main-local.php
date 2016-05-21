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
