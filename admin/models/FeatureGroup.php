<?php

namespace admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\FeatureGroupItem;

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
class FeatureGroup extends \common\models\FeatureGroup
{
   /* 
    *
    *   To save created, modified user & date time 
    */
    public function behaviors()
    {
        return parent::behaviors();
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
   			$featuregroup= FeatureGroup::find()
     			->where(['!=', 'group_status', 'Deactive'])
     			->andwhere(['!=', 'trash', 'Deleted'])
          ->orderBy('group_name')
     			->all();

   			return ArrayHelper::map($featuregroup, 'group_id', 'group_name');
   	}

  // Status Image title
    public function statusTitle($status)
    {           
        if($status == 'Active')
            return 'Activate';

        return 'Deactivate';
    }
}
