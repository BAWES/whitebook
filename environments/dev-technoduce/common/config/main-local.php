<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.1.179;dbname=whitebook',
            'username' => 'svnuser',
            'password' => 'pXc9nbmrnC',
            'charset' => 'utf8',
            'tablePrefix'=>'whitebook_',
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'http://dev.thewhitebook.com.kw',
        ],
        'urlManagerVendor' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'http://devvendor.thewhitebook.com.kw',
        ],
        'urlManagerAdmin' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'http://devadmin.thewhitebook.com.kw',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'htmlLayout' => 'layouts/whitebook-html',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'a.mariyappan88@gmail.com',
                'password' => 'TN20aq8298',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
    ],
];
