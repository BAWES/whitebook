<?php
return [
    'components' => [
         'urlManager' => [
         'class' => 'yii\web\UrlManager',
		 //  Disable index.php
		  'showScriptName' => false,
		 //  Disable r= routes
        'enablePrettyUrl' => false,
        'enableStrictParsing' => true,
        'rules' => [
				//'pattern' => 'route',
				'login' => 'site/login',
				'password' => 'site/password',
				'users' => 'users/index',				
				'<module:admin>/<action:\w+>' => '<module>/default/<action>',
				'<module:admin>/<action:\w+>?<id:\d+>' => '<module>/default/<action>',
				'<module:admin>/<controller:\w+>' => '<module>/<controller>/index',
				'<module:admin>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>'              
				],		
        ],
    ],
    'params' => [
        // list of parameters
    ],
];
