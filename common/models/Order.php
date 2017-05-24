<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "whitebook_order".
 *
 * @property integer $order_id
 * @property string $order_token
 * @property string $created_datetime
 * @property string $modified_datetime
 *
 * @property Booking[] $bookings
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['order_token'], 'string', 'max' => 13],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_token' => 'Order Token',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['order_id' => 'order_id']);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!$this->order_token) {
            $this->order_token = $this->generateToken();
        }

        return true;
    }

    public function generateToken()
    {
        $unique = Yii::$app->getSecurity()->generateRandomString(13);

        $exists = Order::findOne([
                'order_token' => $unique
            ]); ;

        if (!empty($exists)) {
            return $this->generateToken();
        }

        return $unique;
    }
}
