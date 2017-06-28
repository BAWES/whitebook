<?php

namespace api\modules\v1;

use Yii;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        //Can Initialize / add params to this module here

        $language = Yii::$app->request->get('language');

        if($language == 'ar') 
        {
            Yii::$app->language = 'ar';
        }
        else
        {
            Yii::$app->language = 'en';
        }
    }

}
