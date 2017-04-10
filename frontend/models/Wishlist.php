<?php

namespace frontend\models;

use Yii;
use frontend\models\Customer;

/**
 * This is the model class for table "{{%wishlist}}".
 *
 * @property integer $invitees_id
 * @property integer $event_id
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property integer $created_by
 * @property integer $modified_by
 */
class Wishlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wishlist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'item_id', 'wish_status'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Invitees ID',
            'item_id' => 'Item ID',
            'wish_status' => 'Status',
            'wishlist_id' => 'Wishlist ID',
            'last_updated_date' => 'Last updated date',
        ];
    }

    public function getCustomer()
    {
        return $this->hasMany(Customer::className(), ['customer_id' => 'customer_id']);
    }


    /**
     * @inheritdoc
     * @return \common\models\query\WishlistQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\WishlistQuery(get_called_class());
    }
}
