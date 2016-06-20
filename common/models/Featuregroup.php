<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'whitebook_feature_group';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
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


}
