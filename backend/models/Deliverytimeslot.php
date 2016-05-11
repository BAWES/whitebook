<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_delivery_timeslot}}".
 *
 * @property string $timeslot_id
 * @property string $vendor_id
 * @property string $timeslot_day
 * @property string $timeslot_start_time
 * @property string $timeslot_end_time
 * @property integer $timeslot_maximum_orders
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Deliverytimeslot extends \common\models\Deliverytimeslot
{
    public static function deliverytimeslot($day)
    {
        return $time_slot = Deliverytimeslot::find()->where(['vendor_id'=>Yii::$app->user->getId(), 'timeslot_day'=>$day])->asArray()->all();;
    }


    public static function vendor_deliverytimeslot($id,$day)
    {
        return $time_slot = Deliverytimeslot::find()->where(['vendor_id'=>$id, 'timeslot_day'=>$day])->asArray()->all();;
    }

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

