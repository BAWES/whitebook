<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
* This is the model class for table "whitebook_item_type".
*
* @property string $type_id
* @property string $type_name
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*
* @property VendorItem[] $vendorItems
*/
class ItemType extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'whitebook_item_type';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['type_name'],'required','on' => 'insert'],
            [['type_name'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
            [['type_name'], 'string', 'max' => 100],
            ['type_name','typenamevalidation'],
        ];
    }


    /**
    * Scenarios for validation and massive assignment
    */
    public function scenarios() {
        $scenarios['default'] = ['type_name'];
        $scenarios['insert'] = ['type_name'];
        //$scenarios['uploadImage'] = ['slide_image'];

        return $scenarios;
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
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'type_name' => 'Item name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    public  function typenamevalidation($attribute_name)
    {
        if(!empty($this->type_name) ){
            $modelq = ItemType::find()
            ->where(['type_name' => $this->type_name])
            ->andWhere(['!=', 'type_id', $this->type_id])
            ->one();            
            if($modelq){
                $this->addError('type_name','Please enter a unique Type name');
            }
        }
    }

    public static function loaditemtype()
    {
        $itemtype= ItemType::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->all();
        $itemtype=ArrayHelper::map($itemtype,'type_id','type_name');
        return $itemtype;
    }

    public static function loadvendorname()
    {
        $vendorname= Vendor::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->all();
        $vendorname=ArrayHelper::map($vendorname,'vendor_id','vendor_name');
        return $vendorname;
    }

    //Item type name should in vendor view tab
    public static function itemtypename($id)
    {
        $itemtype= ItemType::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->andwhere(['=', 'type_id', $id])
        ->one();

        return $itemtype['type_name'];
    }

    public static function itemtypename_ar($id)
    {
        $itemtype= ItemType::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->andwhere(['=', 'type_id', $id])
        ->one();
        
        return $itemtype['type_name_ar'];
    }
}
