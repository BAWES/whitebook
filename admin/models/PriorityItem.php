<?php

namespace admin\models;

use Yii;
use admin\models\VendorItem;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "whitebook_priority_item".
*
* @property string $priority_id
* @property string $item_id
* @property string $priority_level
* @property string $priority_start_date
* @property string $priority_end_date
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*
* @property VendorItem $item
*/
class PriorityItem extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";

    /**
    * @inheritdoc
    */

    /* Attribute created for filter (create page). */
    public $filter_start;
    public $filter_end;
    public $item_status;

    public static function tableName()
    {
        return 'whitebook_priority_item';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['item_id',  'priority_start_date', 'priority_end_date', 'priority_level'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['priority_level', 'trash'], 'string'],
            [['priority_start_date', 'priority_end_date', 'created_datetime', 'modified_datetime'], 'safe'],
            ['item_id', 'unique', 'targetAttribute' => ['priority_level', 'priority_start_date','priority_end_date'],'message' => 'Item name, Priority level, Start date, End date are already exists .' ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'priority_id' => 'Priority Name',
            'item_id' => 'Item Name',
            'priority_level' => 'Priority level',
            'priority_start_date' => 'Priority Start Date',
            'priority_end_date' => 'Priority End Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash'
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }
    public function getpriorityitem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    public function getvendoritem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    /*
    *
    *   To save created, modified user & date time
    */
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

    public function getItemName($id)
    {
        $id=explode(',',$id);
        //print_r ($id);die;
        foreach($id as $i)
        {
            $model = VendorItem::find()->where(['item_id'=>$i])->one();
            $item[]=$model['item_name'];
        }
        return $item=implode(',',$item);
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
