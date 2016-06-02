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

      public function behaviors()
    {
        return parent::behaviors();
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
                ->where(['not exists', $subQuery])
                ->andwhere(['p2.status'=> 'Active'])
                ->andwhere(['p2.trash'=> 'Default']);
        $command = $query->createCommand();
    $addresstype=($command->queryall());
    
    $addresstype=ArrayHelper::map($addresstype,'type_id','type_name');
    return $addresstype;
  }

  	public static function loadAddress()
  	{
  		$addresstype = Addresstype::find()
  		->select(['type_id','type_name'])
  		->where(['status'=>'Active'])
      ->andWhere(['trash'=>'Default'])
      ->all();
  		
  		return $addresstype=ArrayHelper::map($addresstype,'type_id','type_name');
  	}
}
