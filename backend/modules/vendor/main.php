<?php
return [
    'components' => [
         'urlManager' => [
         'class' => 'yii\web\UrlManager',
		  'showScriptName' => false,
        'enablePrettyUrl' => false,
        'enableStrictParsing' => true,
        'rules' => [
				//'pattern' => 'route',
				'login' => 'site/login',
				'password' => 'site/password',
				'users' => 'users/index',				
				'<module:vendor>/<action:\w+>' => '<module>/default/<action>',
				'<module:vendor>/<action:\w+>?<id:\d+>' => '<module>/default/<action>',
				'<module:vendor>/<controller:\w+>' => '<module>/<controller>/index',
				'<module:vendor>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>'              
				],		
        ],
    ],
    'params' => [
    ],
];
