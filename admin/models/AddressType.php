<?php

// address Type in admin

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
class AddressType extends \common\models\AddressType
{

    public function behaviors()
    {
        return parent::behaviors();
    }

    public static function loadAddresstype()
    {
        $addresstype = AddressType::find()
            ->where([
                'status'=> 'Active',
                'trash'=> 'Default'
            ])
            ->all();

        $addresstype = ArrayHelper::map($addresstype,'type_id','type_name');

        return $addresstype;
    }

    public static function loadAddress()
    {
        $addresstype = AddressType::find()
            ->select(['type_id','type_name'])
            ->where(['status'=>'Active'])
            ->andWhere(['trash'=>'Default'])
            ->all();

        return ArrayHelper::map($addresstype,'type_id','type_name');
    }
}
