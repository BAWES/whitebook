<?php

namespace backend\models;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%area}}".
 *
 * @property string $area_id
 * @property integer $area_name
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Vendoraddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_contact_no','address_text','area_id'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'area_id' => 'Area',
            'vendor_contact_no' => 'Contact number',
        ];
    }

    	public static function loadaddress()
	{
		 $address=Vendoraddress::find()->all();
		return $address=ArrayHelper::map($area,'address_id','address_text');
	}
	
	
	public static function areashow($id)
	{
		echo $id;
		$command = \Yii::$app->DB->createCommand(
		'SELECT (`area_name`) FROM whitebook_area WHERE area_id='.$id);
		$area = $command->queryAll();
		$k= ($area[0]['area_name']);
		return $k;
	}

}
