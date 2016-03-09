<?php
namespace backend\modules\admin;
use Yii;
use yii\helpers\Url;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\admin\controllers';

    public function init()
    {		
        return $this->layout = '/admin/main.php'; 
    }   
    
	public function  beforeAction($action)
    {			
		$session = Yii::$app->session;
		if($session['type'] != '')
		{			
			if($session['type'] == 'Vendor'){						
				$url =  Yii::$app->urlManager->createUrl(['vendor/default/dashboard']);
				Yii::$app->getResponse()->redirect($url);			 
			}					
		}	
		return true;			
	}    
}
