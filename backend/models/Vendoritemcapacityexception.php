<?php

namespace backend\models;
use backend\models\Vendoritem;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "{{%vendor_item_capacity_exception}}".
 *
 * @property string $exception_id
 * @property string $item_id
 * @property string $exception_date
 * @property integer $exception_capacity
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Vendoritemcapacityexception extends \common\models\Vendoritemcapacityexception
{

    /* 
    *
    *   To save created, modified user & date time 
    */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
           $this->created_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->created_by = \Yii::$app->user->identity->id;
        } 
        else {
           $this->modified_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->modified_by = \Yii::$app->user->identity->id;
        }
           return parent::beforeSave($insert);
    }

}
