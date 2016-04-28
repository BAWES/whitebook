<?php

namespace admin\models;
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
class Addresstype extends \common\models\Addresstype
{

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
}
