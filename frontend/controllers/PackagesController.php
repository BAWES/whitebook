<?php

namespace frontend\controllers;

use Yii;
use common\models\Package;

/**
* Packages controller.
*/
class PackagesController extends BaseController
{	
	public function actionIndex()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'] .' | Event Packages';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'Event Packages in The White Book - Event Planning Platform']);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => 'Event Packages in The White Book - Event Planning Platform']);

    	$packages = Package::find()
    		->where(['status' => 1])
    		->all();

    	return $this->render('index', [
            'packages' => $packages,
        ]);
    }
}