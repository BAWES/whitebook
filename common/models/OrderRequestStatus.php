<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use common\models\Order;
use common\models\Vendor;
use common\models\Customer;
use common\models\SuborderItemPurchase;

/**
 * This is the model class for table "{{%order_request_status}}".
 *
 * @property integer $request_id
 * @property integer $suborder_id
 * @property integer $order_id
 * @property integer $vendor_id
 * @property string $request_status
 * @property string $request_note
 * @property string $created_datetime
 * @property string $modified_datetime
 */
class OrderRequestStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_request_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'suborder_id', 'vendor_id'], 'required'],
            [['order_id', 'suborder_id', 'vendor_id'], 'integer'],
            [['request_status', 'request_note'], 'string'],
            [['created_datetime', 'modified_datetime', 'request_token'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => Yii::t('app', 'Request ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'request_status' => Yii::t('app', 'Request Status'),
            'request_note' => Yii::t('app', 'Request Note'),
            'created_datetime' => Yii::t('app', 'Created On'),
            'modified_datetime' => Yii::t('app', 'Modified On'),
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

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        
        if (!$this->request_token) {
            $this->request_token = $this->generateToken();
        }
        
        return true;
    }

    public function generateToken()
    {
        $unique = Yii::$app->getSecurity()->generateRandomString(13);

        $exists = OrderRequestStatus::findOne([
                'request_token' => $unique
            ]); ;
        
        if (!empty($exists)) {
            return $this->generateToken();
        }
        
        return $unique;
    }

    /*
     * Get order detail
     */
    public function getOrderDetail()
    {
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }

    /*
     * get vendor detail
     */
    public function getVendorDetail()
    {
        return $this->hasOne(Vendor::className(),['vendor_id'=>'vendor_id']);
    }

    public static function approved($request) 
    {
        $order = Order::findOne($request->order_id);

        $suborder = Suborder::find()
            ->where([
                    'order_id' => $request->order_id,
                    'vendor_id' => $request->vendor_id
                ])
            ->one();

        $customer = Customer::findOne($order->customer_id);

        //Send Email to customer

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("customer/request-approved",
            [
                "model" => $suborder,
                "customer" => $customer,
                "lnk_payment" => Yii::$app->urlManagerFrontend->createUrl(["payment/index", 'token' => $request->request_token])
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($customer->customer_email)
            ->setSubject('Order request approved!')
            ->send();
    }

    public static function declined($model) 
    {
        $order = Order::findOne($model->order_id);

        $vendor = Vendor::findOne($model->vendor_id);

        $customer = Customer::findOne($order->customer_id);

        //get items 
        $items = SuborderItemPurchase::find()
            ->select('{{%vendor_item}}.item_id, {{%vendor_item}}.item_name')
            ->innerJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%suborder_item_purchase}}.item_id')
            ->innerJoin('{{%suborder}}', '{{%suborder}}.suborder_id = {{%suborder_item_purchase}}.suborder_id')
            ->where([
                '{{%suborder}}.order_id' => $model->order_id,
                '{{%suborder}}.vendor_id' => $model->vendor_id
            ])
            ->asArray()
            ->all();

        $items = implode(', ', ArrayHelper::map($items, 'item_id', 'item_name'));

        //Send Email to customer

        if(Yii::$app->params['notify_customer_request_decline']) 
        {
            Yii::$app->mailer->htmlLayout = 'layouts/empty';

            Yii::$app->mailer->compose("customer/request-declined",
                [
                    "model" => $model,
                    "customer" => $customer,
                    "vendor" => $vendor,
                    "items" => $items,
                    "logo_1" => Url::to("@web/uploads/twb-logo-horiz-white.png", true),
                    "logo_2" => Url::to("@web/uploads/twb-logo-trans.png", true),
                ])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($customer->customer_email)
                ->setSubject('Order request rejected!')
                ->send();
        }

        //send to admin 

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("admin/request-declined",
            [
                "model" => $model,
                "customer" => $customer,
                "vendor" => $vendor,
                "items" => $items,
                "logo_1" => Url::to("@web/uploads/twb-logo-horiz-white.png", true),
                "logo_2" => Url::to("@web/uploads/twb-logo-trans.png", true),
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Order request rejected!')
            ->send();
    }
}