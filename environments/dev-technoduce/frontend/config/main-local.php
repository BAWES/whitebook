<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManager' => [
          'enablePrettyUrl' => true,
          'rules' => [
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
                'product/addevent'=>'product/addevent',
                // BEGIN define  page name
                'site/home' => 'site/index/',
                'activate' => 'site/activate/',
                'plan' => 'plan/plans/',
                'shop' => '/site/shop/',
                // list of category products page
                'products/<slug:[A-Za-z0-9\_-]+>' => 'plan/plan/',
                'experience' => 'site/experience',
                'directory' => 'site/directory',
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
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
