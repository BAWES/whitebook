<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['gii'],
    'modules' => [],
    'homeUrl' => '/backend/web',     
    'components' => [
	'request'=>[
					   'enableCsrfValidation'=>false,
			   ],
        'cache' =>
         [
					'class' => 'yii\caching\FileCache',
		 ],
      'authManager'=>
			[
				 'class' => 'yii\rbac\DbManager',
				 'defaultRoles' => ['admin', 'author'],
			],
		 'urlManager' => [		 
         'class' => 'yii\web\UrlManager',
		 //  Disable index.php
		  'showScriptName' => false,
		 //  Disable r= routes
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
        'urlManagerBackEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/backend/web/',            
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'themeURL' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/backend/web/themes/default',            
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],		
        'user' => [		
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => 
                  [
					'class' => 'yii\log\FileTarget',
					'categories' => ['yii\web\HttpException:404'],
					'levels' => ['error', 'warning'],
				 ],			
            ],
        ],      
			'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
		],
		'newcomponent' =>[
			'class'=> 'backend\components\MainComponent',
		],
		'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        YII_ENV_DEV ? '' : ''
                     ]
                  ],
              ],
          ],    
       ], 		
		'modules' => [
			'gii' => 'yii\gii\Module',
			'allowedIPs' => ['192.168.1.112','192.168.1.235'],// adjust this to your needs
            'admin' => ['class'=>'backend\modules\admin\Module'],
            'vendor' => ['class'=>'backend\modules\vendor\Module'],
    ],
    'params' => $params,
   
];
