<?php

namespace common\models;
use yii\db\Query;
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
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
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

	public static function loadAddresstype()
	{
		$subQuery = (new Query())
                ->select('*')
                ->from('{{%address_question}} t')
                ->where('t.address_type_id = p2.type_id')
                ->andwhere('t.trash = "Default"');
		$query = (new Query())
                ->select(['type_id','type_name'])
                ->from('{{%address_type}} p2')
                ->where(['exists', $subQuery])
                ->andwhere(['status'=> 'Active'])
                ->andwhere(['trash'=> 'Default']);
        $command = $query->createCommand();
		$Addresstype=($command->queryall());
		
		$Addresstype=ArrayHelper::map($Addresstype,'type_id','type_name');
		return $Addresstype;
	}
		public static function loadAddress()
	{
		$Addresstype = Addresstype::find()
		->select(['type_id','type_name'])
		->where(['status'=>'Active'])->asarray()->all();
		
		$Addresstype=ArrayHelper::map($Addresstype,'type_id','type_name');
		return $Addresstype;
	}
	public static function getAddresstype($id)
    {
		$model = Addresstype::find()->where(['type_id'=>$id])->one();
        return $model->type_name;
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
