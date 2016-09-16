<?php

namespace frontend\models;

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
        return $this->hasMany(CustomerAddress::className(), 
          ['address_type_id' => 'type_id']);
    }

    public static function loadAddresstype() {

        $query = Addresstype::find()
                    ->select(['type_id', 'type_name', 'type_name_ar'])
                    ->where([
                        'status' => 'Active',
                        'trash' => 'Default'
                      ]);
        
        $command = $query->createCommand();

        $addresstype = $command->queryall();
        
        if (Yii::$app->language == 'en') {
          $addresstype = ArrayHelper::map($addresstype,'type_id','type_name');
        } else {
          $addresstype = ArrayHelper::map($addresstype,'type_id','type_name_ar');
        }
        
        return $addresstype;
    }

  	public static function loadAddress()
  	{
    		$addresstype = Addresstype::find()
      		->select(['type_id','type_name'])
      		->where(['status'=>'Active'])
          ->andWhere(['trash'=>'Default'])
          ->all();
      		
    		return $addresstype = ArrayHelper::map($addresstype,'type_id','type_name');
  	}

    public static function type_name($type_id){
        $type = self::findOne($type_id);

        if(!$type)
          return false;

        if(Yii::$app->language == 'en') {
          return $type->type_name;
        }else{
          return $type->type_name_ar;
        }
    }
}
