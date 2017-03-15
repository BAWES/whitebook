<?php

$params = array_merge(
    require(__DIR__.'/../../common/config/params.php'),
    require(__DIR__.'/../../common/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'The Whitebook',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\Customer',
            'enableAutoLogin' => true,
            'on '.\yii\web\User::EVENT_BEFORE_LOGIN => ['common\models\Customer', 'handleBeforeLogin'],

        ],
        'session' => [
            'name' => 'app-frontend',
        ],
        // Override the urlManager component for i18n plugin
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,

            // Url Normalizer as Added in Yii 2.0.10
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => 301, // permanent redirect
            ],

            //Ignore adding /ar/ to the following url patterns
            'ignoreLanguageUrlPatterns' => [
                //'#^site/(login|register)#' => '#^(login|register)#',
                //'#^api/#' => '#^api/#',
                //'#^assets/#' => '#^assets/#',
            ],

            // List all supported languages here
            // Make sure, you include your app's default language.
            'languages' => ['en', 'ar'],

            // Url Rules for Frontend
            'rules' => [
                  '' => 'site/index',
                  'payment/<token:[0-9]+>' => 'payment/index',
                  'payment/tap' => 'payment/tap/index',
                  'payment/cod' => 'payment/cod/index',
                  'my-events' => 'events/index',
                  'things-i-like' => 'things-i-like/index',
                  'themes' => 'themes/index',
                  'directory' => 'directory/index',
                  'cart' => 'cart/index',
                  'browse/booking' => 'browse/booking',
                  'browse/final-price' => 'browse/final-price',
                  'browse/save-note' => 'browse/save-note',
                  'browse/<slug:[A-Za-z0-9\_-]+>' => 'browse/list',
                  'browse/detail/<slug:[A-Za-z0-9\_-]+>' => 'browse/detail',
                  'themes/<slug:[A-Za-z0-9\_-]+>/<themes:[A-Za-z0-9\_-]+>' => 'themes/detail',
                  /*'vendor/<slug:[A-Za-z0-9\_-]+>/<vendor:[A-Za-z0-9\_-]+>' => 'directory/profile',*/
                  'vendor/<vendor:[A-Za-z0-9\_-]+>' => 'directory/profile',
                  'events/pdf/<slug:[A-Za-z0-9\_-]+>' => 'events/pdf',
                  'events/detail/<slug:[A-Za-z0-9\_-]+>' => 'events/detail',
                  'events/public/<token:[A-Za-z0-9\_-]+>' => 'events/public',
                  'sitemap.xml' => 'sitemap/index',
                  'edit-profile' => 'users/edit_profile',
                  'create-event' => 'users/create_event',
                  'event-slider' => 'product/event_slider',
                  'update-event' => 'users/update_event',
                  'add-event' => 'users/add_event',
                  'search' => 'site/search',
                  'directorysearch' => 'site/searchdirectory',
                  'search-result/<search:[A-Za-z0-9\_-]+>' => 'site/searchresult',
                  'signup' => 'users/signup',
                  'emailcheck' => 'users/email_check',
                  'login' => 'users/login',
                  'forget' => 'users/forget_password',
                  'password-reset' => 'users/password_reset',
                  'reset/<cust_id:[0-9a-zA-Z\-&]+>' => 'users/reset_confirm',
                  'logout' => 'users/logout',
                  'account-settings' => 'users/account_settings',
                  'events' => 'users/events',
                  'event-details/<slug:[A-Za-z0-9\_-]+>' => 'users/eventdetails',
                  'excel/<slug:[A-Za-z0-9\_-]+>' => 'users/excel',
                  'inviteesearch' => 'event-invitees/index',
                  'event-invitees' => 'event-invitees/addinvitees',
                  'category/<name:[0-9a-zA-Z\-&]+>' => 'category/category_products',
                  'confirm-email/<key:[0-9a-zA-Z\-&]+>' => 'users/confirm_email',
                  'add-to-wishlist' => 'users/add_to_wishlist',
                  'remove-from-wishlist' => 'users/remove_from_wishlist',
                  'wishlist' => 'users/wishlist',
                  'load_more' => 'users/load_more_events',
                  // BEGIN define  page name
                  'activate' => 'site/activate/',
                  'plan' => 'plan/plans/',
                  'shop' => '/site/shop/',
                  // list of category products page
                  'products/<slug:[A-Za-z0-9\_-]+>' => 'plan/plan/',
                  'experience' => 'site/experience',
                  'contact-us' => 'site/contact',

                  // particular products detail page
                  //'product/<slug:[A-Za-z0-9\_-]+>' => 'product/product/',

                  // BEGIN define  page name
                  'load_more_wishlist' => 'users/load_more_wishlist',
                  'pro-eventdetails' => 'product/eventdetails',
                  // particular vendor detail page
                  'pending_items' => 'site/pending_items',
                  'experience/<slug:[0-9a-zA-Z\-&]+>' => 'site/vendor_profile',
                  '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                  '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                  '<action:\w+>' => '<controller>/<action>',
                  '<slug:[A-Za-z0-9\_-]+>' => 'site/cmspages',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],
    'params' => $params,
];
