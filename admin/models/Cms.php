<?php

namespace admin\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%cms}}".
 *
 * @property integer $page_id
 * @property string $page_name
 * @property string $page_content
 * @property integer $page_order
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Cms extends \common\models\Cms
{

      public function behaviors()
    {
        return parent::behaviors();
    }

    
   public static function content($content)
    {       
            return strip_tags($content); 
    }   

    public function statusImageurl($img_status)
    {
        if($img_status == 'Active')     
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }

    // Status Image title
    public function statusTitle($status)
    {           
    if($status == 'Active')     
        return 'Activate';
        return 'Deactivate';
    }

}
