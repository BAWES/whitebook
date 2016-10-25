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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name'],'unique'],
            [['type_name'], 'required'],
            [['created_by', 'modified_by', 'created_datetime', 'modified_datetime', 'trash','status'], 'safe'],
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
            'status' => 'Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Date',
            'modified_datetime' => 'Modified Date',
            'trash' => 'Trash',
        ];
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
