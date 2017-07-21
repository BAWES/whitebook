<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use common\models\Booking;

/**
 * This is the model class for table "whitebook_vendor_review".
 *
 * @property integer $review_id
 * @property string $customer_id
 * @property string $vendor_id
 * @property integer $rating
 * @property string $review
 * @property integer $approved
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $customer
 * @property Vendor $vendor
 */
class VendorReview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'vendor_id', 'rating', 'approved'], 'integer'],
            [['customer_id', 'vendor_id', 'rating', 'review'], 'required'],
            [['customer_id'], 'validateCustomer'],
            [['review'], 'string', 'min' => 10],
            [['created_at', 'updated_at'], 'safe'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }
    
    public function validateCustomer($attribute, $params, $validator)
    {
        $booking = Booking::find()
            ->where([
                'customer_id' => $this->$attribute,
                'vendor_id' => $this->vendor_id
            ])
            ->one();

        if (!$booking) {
            $this->addError($attribute, Yii::t('frontend', 'You need to place order to add review'));
        }
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
            'review_id' => Yii::t('frontend', 'Review ID'),
            'customer_id' => Yii::t('frontend', 'Customer ID'),
            'vendor_id' => Yii::t('frontend', 'Vendor ID'),
            'rating' => Yii::t('frontend', 'Rating'),
            'review' => Yii::t('frontend', 'Review'),
            'approved' => Yii::t('frontend', 'Approved'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    public function getVendorName() {
        return $this->vendor->vendor_name;
    }

    public function getCustomerName() {
        return $this->customer->customer_name;
    }

    public function notifyVendor($model)
    {
        Yii::$app->mailer->htmlLayout = 'layouts/empty';
        
        Yii::$app->mailer->compose("vendor/review-added",
            [
                "model" => $model,
                "image_1" => Url::to("@web/twb-logo-trans.png", true),
                "image_2" => Url::to("@web/twb-logo-horiz-white.png", true)
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo($model->vendor->vendor_contact_email)
            ->setSubject('New Review Added')
            ->send();
    }
}
