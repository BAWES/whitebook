<?php
namespace backend\modules\vendor;
use Yii;

class Module extends \yii\base\Module
{
	
	public $layout;
    public $controllerNamespace = 'backend\modules\vendor\controllers';
	// This code over write	module layout
    public function init()
    {			
 	 	 $this->layout = '/vendor/main.php';  
	 	  Yii::$app->set('user', [
        'class' => 'yii\web\User',
        'identityClass' => 'backend\models\Vendor',
        'enableAutoLogin' => false,       
    ]);
		  
    }
    
	public function  beforeAction($action)
    {					
		$session = Yii::$app->session;
		if($session['type'] != '')
		{						
			if($session['type'] == 'Admin'){
				$url =  Yii::$app->urlManager->createUrl(['admin/site/index']);
				Yii::$app->getResponse()->redirect($url);			 
			}				
		} 				
		return true;
	} 
}
?>
