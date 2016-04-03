<?php

namespace common\models;

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
class Featuregroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_feature_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name',], 'required'],
            [['group_name',],'unique'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
            [['group_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group Name',
            'group_name' => 'Group Name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
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
