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
            'identityClass' => 'api\models\Customer',
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
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [ // CartController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/cart',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET count' => 'cart-count',
                        'POST' => 'add',
                        'PATCH' => 'update',
                        'DELETE' => 'remove',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS count' => 'options',
                    ]
                ],
                [ // AccountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/account',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'PATCH' => 'update',
                        'POST contact' => 'contact',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS index' => 'options',
                        'OPTIONS update' => 'options',
                        'OPTIONS contact' => 'options',
                    ]
                ],
                [ // CategoryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/category',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'category-listing',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [ // ProductController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/product',
                    'pluralize' => false,
                    'patterns' => [
                        'GET detail' => 'product-detail',
                        'GET list' => 'category-products',
                        'GET area' => 'product-areas',
                        'GET time-slot' => 'product-delivery-time-slot',
                        'GET capacity' => 'item-capacity',
                        'GET theme' => 'load-all-themes',
                        'GET vendors' => 'load-all-vendor',
                        'POST event' => 'add-product-to-event',
                        'POST final-price' => 'final-price',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS list' => 'options',
                        'OPTIONS area' => 'options',
                        'OPTIONS time-slot' => 'options',
                        'OPTIONS capacity' => 'options',
                        'OPTIONS theme' => 'options',
                        'OPTIONS vendors' => 'options',
                        'OPTIONS final-price' => 'options',
                    ]
                ],
                [ // EventController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/event',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'event-list',
                        'GET type' => 'event-type-list',
                        'GET detail' => 'event-detail',
                        'POST' => 'event-create',
                        'PATCH' => 'event-update',
                        'DELETE' => 'event-remove',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
						'OPTIONS type' => 'options',
                        'OPTIONS detail' => 'options',
                    ]
                ],
                [ // WishlistController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/wishlist',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'wishlist-list',
                        'GET exist' => 'is-item-exist',
                        'POST' => 'wishlist-add',
                        'DELETE' => 'wishlist-remove',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS exist' => 'options',
                    ]
                ],
                [ // AddressController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/address',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'address-list',
                        'GET type' => 'address-type-list',
                        'GET view' => 'address-view',
                        'GET questions' => 'address-questions',
                        'GET location' => 'get-location',
                        'POST' => 'address-add',
                        'PATCH' => 'address-update',
                        'DELETE' => 'address-remove',

                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS type' => 'options',
                        'OPTIONS view' => 'options',
                        'OPTIONS questions' => 'options',
                        'OPTIONS location' => 'options',
                    ]
                ],
                [ // CheckoutController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/checkout',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list-cart-items',
                        'GET payment-getaway' => 'payment-getaway-list',
                        'GET address' => 'address',
                        'GET success' => 'success',
                        'GET list-with-address' => 'cart-item-with-address',
                        'GET delivery-area' => 'delivery-area',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS payment-getaway' => 'options',
                        'OPTIONS address' => 'options',
                        'OPTIONS success' => 'options',
                        'OPTIONS list-with-address' => 'options'
                    ]
                ],
                [ // BookingController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/booking',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [ // PackageController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/package',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options'
                    ]
                ],
                [ // ThemeController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/theme',
                    'patterns' => [
                        'GET' => 'list',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [ // DirectoryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/directory',
                    'patterns' => [
                        'GET' => 'list',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [ // DirectoryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/cms',
                    'patterns' => [
                        'GET <id>' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS <id>' => 'options'
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
