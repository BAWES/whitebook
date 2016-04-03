<?php

namespace backend\models;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%address_type}}".
 *
 * @property string $type_id
 * @property string $type_name
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_date
 * @property string $modified_date
 * @property string $trash
 *
 * @property CustomerAddress[] $customerAddresses
 */
class Addresstype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['type_name'],'unique'],
        [['type_name'], 'required'],
        [['created_by', 'modified_by', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],           
        [['type_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'type_name' => 'Address Type',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Date',
            'modified_datetime' => 'Modified Date',
            'trash' => 'Trash',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerAddresses()
    {
        return $this->hasMany(CustomerAddress::className(), ['address_type_id' => 'type_id']);
    }
    
        public static function statusImageurl($img_status)
	{			
		if($img_status == 'Active')		
		return \Yii::$app->urlManagerBackEnd->createAbsoluteUrl('theme/barebone/assets/img/active.png');
		return \Yii::$app->urlManagerBackEnd->createAbsoluteUrl('theme/barebone/assets/img/inactive.png');
	}
	
	public static function loadAddresstype()
	{
		$command = \Yii::$app->DB->createCommand('SELECT type_id,type_name FROM whitebook_address_type where status="Active" and trash="Default" and not exists (SELECT null FROM whitebook_address_question where address_type_id = whitebook_address_type.type_id and trash="Default")');
		$Addresstype=$command->queryall();
		$Addresstype=ArrayHelper::map($Addresstype,'type_id','type_name');
		return $Addresstype;
	}
		public static function loadAddress()
	{
		$command = \Yii::$app->DB->createCommand('SELECT type_id,type_name FROM whitebook_address_type where status="Active" ');
		$Addresstype=$command->queryall();
		$Addresstype=ArrayHelper::map($Addresstype,'type_id','type_name');
		return $Addresstype;
	}
	public static function getAddresstype($id)
    {		
		$model = Addresstype::find()->where(['type_id'=>$id])->one();
        return $model->type_name;
    }
    	
		
}
