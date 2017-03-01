<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use common\models\Order;
use common\models\Suborder;
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
 * @property datetime $expired_on
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
            [['expired_on','created_datetime', 'modified_datetime', 'request_token'], 'safe'],
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
            'expired_on' => Yii::t('app', 'Expired On'),
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
     * Get sub order detail
     */
    public function getSubOrderDetail()
    {
        return $this->hasOne(Suborder::className(), ['suborder_id' => 'suborder_id']);
    }

    /*
     * Get order detail
     */
    public function getOrderDetail()
    {
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }


    /*
     *  purchased item details
     */

    public function getItemPurchased() {
        return $this->hasOne(SuborderItemPurchase::className(),['suborder_id'=>'suborder_id']);
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

        $suborder = Suborder::findOne($request->suborder_id);

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
            ->setSubject('Item request approved!')
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
                '{{%suborder}}.suborder_id' => $model->suborder_id
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

    public function setExpiredOn($model) {

        if (
            $model->request_status != $model->oldAttributes['request_status'] &&
            $model->request_status == 'Approved'
        )
        $model->expired_on = date('Y-m-d H:i:s',strtotime('+1 day')); // set 24 hour expire date
    }

    /*
     * cron job for booking expire alert before 1 hour;
     */
    public function bookingBeforeExpireAlert() {

        $q = "SELECT wo.customer_id,wors.request_status,wors.request_token,wors.order_id FROM whitebook_order_request_status wors ";
        $q .= "inner join whitebook_order wo on wors.order_id = wo.order_id WHERE wors.expired_on < NOW() - INTERVAL 1 HOUR AND ";
        $q .= "wors.notification_status = '0'";
        $model = Yii::$app->db->createCommand($q);
        $customer = $model->queryAll();
        if ($customer) {
            foreach ($customer as $detail) {
                $customerDetail = Customer::findOne($detail['customer_id']);
                if ($customerDetail) {
                    $message = 'Hello '.$customerDetail->customer_name.' ' .$customerDetail->customer_last_login;
                    $message .= '<br/><br/> Your Approved Booking Item going to Expire in one hour. Please pay pending due before it expire.';
                    $message .= '<br/> Request Token '.$detail['request_token'];
                    $message .= '<br/> Order ID '.$detail['order_id'];

                    echo Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($customerDetail->customer_email)
                        ->setSubject('Whitebook : Expiring booking token #'.$detail['request_token'])
                        ->setTextBody($message)
                        ->send();

                }
            }
        }
    }

    /*
     * cron job for booking expired notification;
     */
    public function bookingAfterExpiredAlert() {

        $q = "SELECT wo.customer_id,wors.request_id,wors.request_status,wors.request_token,wors.order_id FROM whitebook_order_request_status wors ";
        $q .= "inner join whitebook_order wo on wors.order_id = wo.order_id WHERE wors.expired_on >= NOW() AND ";
        $q .= "wors.notification_status = '0'";
        $model = Yii::$app->db->createCommand($q);
        $customer = $model->queryAll();
        if ($customer) {
            $q = "update whitebook_order_request_status set request_status = 'Expired', notification_status = '1'";
            $q .= "WHERE expired_on >= NOW() AND notification_status = '0'";
            Yii::$app->db->createCommand($q)->execute();

            foreach ($customer as $detail) {
                $customerDetail = Customer::findOne($detail['customer_id']);
                if ($customerDetail) {
                    $message = 'Hello '.$customerDetail->customer_name.' ' .$customerDetail->customer_last_login;
                    $message .= '<br/><br/> Your Approved Booking Item is Expired.';
                    $message .= '<br/> Request Token '.$detail['request_token'];
                    $message .= '<br/> Order ID '.$detail['order_id'];

                    echo Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($customerDetail->customer_email)
                        ->setSubject('Whitebook : Expired booking token number #'.$detail['request_token'])
                        ->setTextBody($message)
                        ->send();

                }
            }
        }
    }
}






