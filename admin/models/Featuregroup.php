<?php

namespace admin\models;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "whitebook_feature_group".
 *
 * @property string $group_id
 * @property string $group_name
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property FeatureGroupItem[] $featureGroupItems
 */
class Featuregroup extends \common\models\Featuregroup
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatureGroupItems()
    {
        return $this->hasMany(FeatureGroupItem::className(), ['group_id' => 'group_id']);
    }
    
    public static function statusImageurl($img_status)
   	{			
   		if($img_status == 'Active')		
   		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
   		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
   	}
	
	   
    public static function loadfeaturegroup()
   	{       
   			$featuregroup= Featuregroup::find()
   			->where(['!=', 'group_status', 'Deactive'])
   			->andwhere(['!=', 'trash', 'Deleted'])
   			->all();
   			$featuregroup=ArrayHelper::map($featuregroup,'group_id','group_name');
   			return $featuregroup;
   	}
}
