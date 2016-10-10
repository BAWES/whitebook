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
    
    public static function loadAddresstype()
    {

      $addresstype = Addresstype::find()
        ->where([
          'p2.status'=> 'Active',
          'p2.trash'=> 'Default'
        ])
        ->all();
      
      $addresstype = ArrayHelper::map($addresstype,'type_id','type_name');

      return $addresstype;
    }

  	public static function loadAddress()
  	{
  		$addresstype = Addresstype::find()
    		->select(['type_id','type_name'])
    		->where(['status'=>'Active'])
        ->andWhere(['trash'=>'Default'])
        ->all();
    		
  		return ArrayHelper::map($addresstype,'type_id','type_name');
  	}
}
