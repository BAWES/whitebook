<?php

$params = array_merge(
require(__DIR__.'/../../common/config/params.php'),
require(__DIR__.'/../../common/config/params-local.php'),
require(__DIR__.'/params.php'),
require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\modules\v1\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            // Accept and parse JSON Requests
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'api\models\customer',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [ // AuthController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/auth',
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'POST create-account' => 'create-account',
                        'POST request-reset-password' => 'request-reset-password',
                        // OPTIONS VERBS
                        'OPTIONS login' => 'options',
                        'OPTIONS create-account' => 'options',
                        'OPTIONS request-reset-password' => 'options',
                        'OPTIONS resend-verification-email' => 'options',
                    ]
                ],

                [ // SearchController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/search',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                    ]
                ],
                [ // CartController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/cart',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'listing',
                        'GET count' => 'cart-count',
                        'POST' => 'add',
                        'PATCH' => 'update',
                    ]
                ],
                [ // AccountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/account',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'POST' => 'update',
                    ]
                ],

                [ // CategoryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/category',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'category-listing',
                    ]
                ],

                [ // ProductController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/product',
                    'pluralize' => false,
                    'patterns' => [
                        'GET detail/<product_id>' => 'product-detail',
                        'GET list' => 'category-products',
                        'POST event' => 'add-product-to-event',
                        'GET area' => 'product-areas',
                        'GET time-slot' => 'product-delivery-time-slot',
                    ]
                ],
                [ // EventController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/wishlist',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'wishlist-list',
                        'POST' => 'wishlist-add',
                        'DELETE' => 'wishlist-remove',
                    ]
                ],
                [ // AddressController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/address',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'address-list',
                        'GET type' => 'address-type-list',
                        'GET <id>' => 'address-view',
                        'POST' => 'address-add',
                        'PATCH' => 'address-update',
                        'DELETE <id>' => 'address-remove',
                        'GET questions/<address_type_id>' => 'address-questions',
                    ]
                ],
            ],
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
    ],
    'params' => $params,

];
