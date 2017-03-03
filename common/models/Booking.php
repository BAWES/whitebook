<?php

namespace common\models;

use Yii;
use yii\web\Request;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use common\models\Customer;
use common\models\VendorItemPricing;
use common\models\Vendor;
use common\models\BookingItem;
use common\models\BookingItemMenu;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use common\models\CustomerCartMenuItem;
use common\models\CustomerCart;

/**
 * This is the model class for table "whitebook_booking".
 *
 * @property integer $booking_id
 * @property string $booking_token
 * @property string $vendor_id
 * @property string $customer_id
 * @property string $customer_name
 * @property string $customer_lastname
 * @property string $customer_email
 * @property string $customer_mobile
 * @property string $booking_note
 * @property string $expired_on
 * @property integer $notification_status
 * @property string $commission_percentage
 * @property string $commission_total
 * @property string $payment_method
 * @property string $transaction_id
 * @property string $gateway_percentage
 * @property string $gateway_fees
 * @property string $gateway_total
 * @property string $total_delivery_charge
 * @property string $total_without_delivery
 * @property string $total_with_delivery
 * @property string $total _vendor
 * @property integer $booking_status
 * @property string $ip_address
 * @property string $created_datetime
 * @property string $modified_datetime
 *
 * @property Customer $customer
 * @property Vendor $vendor
 * @property BookingItem[] $bookingItems
 */
class Booking extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_EXPIRED = 3;

    public $status_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_booking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'customer_id', 'notification_status', 'booking_status'], 'integer'],
            [['booking_note'], 'string'],
            [['status_name', 'expired_on', 'created_datetime', 'modified_datetime'], 'safe'],
            [['commission_percentage', 'commission_total', 'gateway_percentage', 'gateway_fees', 'gateway_total', 'total_delivery_charge', 'total_without_delivery', 'total_with_delivery', 'total_vendor'], 'number'],
            [['booking_token'], 'string', 'max' => 13],
            [['customer_name', 'customer_lastname', 'customer_email', 'payment_method', 'transaction_id'], 'string', 'max' => 100],
            [['customer_mobile'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 128],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
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

    public static function statusList()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected'
        ];
    }

    public function getStatusName()
    {
        $statusList = Booking::statusList();

        if(isset($statusList[$this->booking_status]))
            return $statusList[$this->booking_status];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'booking_id' => Yii::t('frontend', 'Booking ID'),
            'booking_token' => Yii::t('frontend', 'Booking Token'),
            'vendor_id' => Yii::t('frontend', 'Vendor ID'),
            'customer_id' => Yii::t('frontend', 'Customer ID'),
            'customer_name' => Yii::t('frontend', 'Customer Name'),
            'customer_lastname' => Yii::t('frontend', 'Customer Lastname'),
            'customer_email' => Yii::t('frontend', 'Customer Email'),
            'customer_mobile' => Yii::t('frontend', 'Customer Mobile'),
            'booking_note' => Yii::t('frontend', 'Booking Note'),
            'expired_on' => Yii::t('frontend', 'Expired On'),
            'notification_status' => Yii::t('frontend', 'Notification Status'),
            'commission_percentage' => Yii::t('frontend', 'Commission Percentage'),
            'commission_total' => Yii::t('frontend', 'Commission Total'),
            'payment_method' => Yii::t('frontend', 'Payment Method'),
            'transaction_id' => Yii::t('frontend', 'Transaction ID'),
            'gateway_percentage' => Yii::t('frontend', 'Gateway Percentage'),
            'gateway_fees' => Yii::t('frontend', 'Gateway Fees'),
            'gateway_total' => Yii::t('frontend', 'Gateway Total'),
            'total_delivery_charge' => Yii::t('frontend', 'Total Delivery Charge'),
            'total_without_delivery' => Yii::t('frontend', 'Total Without Delivery'),
            'total_with_delivery' => Yii::t('frontend', 'Total With Delivery'),
            'total _vendor' => Yii::t('frontend', 'Total  Vendor'),
            'booking_status' => Yii::t('frontend', 'Booking Status'),
            'ip_address' => Yii::t('frontend', 'Ip Address'),
            'created_datetime' => Yii::t('frontend', 'Created Datetime'),
            'modified_datetime' => Yii::t('frontend', 'Modified Datetime'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookingItems()
    {
        return $this->hasOne(BookingItem::className(), ['booking_id' => 'booking_id']);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        
        if (!$this->booking_status) {
            $this->booking_status = Booking::STATUS_PENDING;
        }

        if (!$this->booking_token) {
            $this->booking_token = $this->generateToken();
        }
        
        return true;
    }

    public function generateToken()
    {
        $unique = Yii::$app->getSecurity()->generateRandomString(13);

        $exists = Booking::findOne([
                'booking_token' => $unique
            ]); ;
        
        if (!empty($exists)) {
            return $this->generateToken();
        }
        
        return $unique;
    }

    /** 
     * Add new bookings on checkout confirm 
     */ 
    public function checkoutConfirm()
    {        
        //address ids saved in session from checkout

        $addresses = Yii::$app->session->get('address');

        //default commision

        $default_commision = Siteinfo::info('commission');

        $items = CustomerCart::items();

        //price chart

        $price_chart = array();

        //check if quantity fall in price chart

        foreach ($items as $key => $item) {

            $price_chart[$item['item_id']] = [];

            $price = VendorItemPricing::find()
                ->where(['item_id' => $item['item_id'], 'trash' => 'Default'])
                ->andWhere(['<=', 'range_from', $item['cart_quantity']])
                ->andWhere(['>=', 'range_to', $item['cart_quantity']])
                ->orderBy('pricing_price_per_unit DESC')
                ->one();

            if($price) {
                $price_chart[$item['item_id']]['unit_price'] = $price->pricing_price_per_unit;
            }else{
                $price_chart[$item['item_id']]['unit_price'] = $item['item_price_per_unit'];
            }

            $menu_items = CustomerCartMenuItem::find()
                ->select('{{%vendor_item_menu_item}}.price, {{%vendor_item_menu_item}}.menu_item_name, {{%vendor_item_menu_item}}.menu_item_name_ar, {{%customer_cart_menu_item}}.quantity')
                ->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id')
                ->where(['cart_id' => $item['cart_id']])
                ->asArray()
                ->all();

            $price_chart[$item['item_id']]['menu_price'] = 0;

            foreach ($menu_items as $key => $menu_item) {
                $price_chart[$item['item_id']]['menu_price'] += $menu_item['quantity'] * $menu_item['price'];
            }
        }

        if(Yii::$app->user->isGuest) 
        {
            $customer_id = 0;
            $customer_name = Yii::$app->session->get('customer_name');
            $customer_lastname = Yii::$app->session->get('customer_lastname');
            $customer_email = Yii::$app->session->get('customer_email'); 
            $customer_mobile = Yii::$app->session->get('customer_mobile'); 
        }
        else
        {   
            $customer = Customer::findOne(Yii::$app->user->getId());

            $customer_id = $customer->customer_id;
            $customer_name = $customer->customer_name;
            $customer_lastname = $customer->customer_last_name;
            $customer_email = $customer->customer_email;
            $customer_mobile = $customer->customer_mobile;
        }

        $arr_booking_id = [];

        foreach ($items as $item) {

            $booking = new Booking;
            $booking->vendor_id = $item['vendor_id'];
            $booking->customer_id = $customer_id;
            $booking->customer_name = $customer_name;
            $booking->customer_lastname = $customer_lastname;
            $booking->customer_email = $customer_email;
            $booking->customer_mobile = $customer_mobile;
            $booking->ip_address = Request::getUserIP();
            $booking->save(false);

            //address
            $address_id = $addresses[$item['cart_id']];

            $booking_item = new BookingItem;
            $booking_item->booking_id = $booking->booking_id;
            $booking_item->item_id = $item['item_id'];
            $booking_item->item_name = $item['item_name'];
            $booking_item->item_name_ar = $item['item_name_ar'];
            $booking_item->timeslot = $item['time_slot'];
            $booking_item->area_id = $item['area_id'];
            $booking_item->address_id = $address_id;
            $booking_item->delivery_address = Booking::getPurchaseDeliveryAddress($address_id);
            $booking_item->delivery_date = $item['cart_delivery_date'];
            $booking_item->price = $price_chart[$item['item_id']]['unit_price'];
            $booking_item->quantity = $item['cart_quantity'];
            $booking_item->total = ($price_chart[$item['item_id']]['unit_price'] * $item['cart_quantity']) + $price_chart[$item['item_id']]['menu_price'];
            $booking_item->female_service = $item['female_service'];
            $booking_item->special_request = $item['special_request'];
            $booking_item->save();

            //save menu item
            $menu_items = CustomerCartMenuItem::find()
                ->select([
                    '{{%vendor_item_menu}}.menu_id',
                    '{{%vendor_item_menu}}.menu_name',
                    '{{%vendor_item_menu}}.menu_name_ar',
                    '{{%vendor_item_menu}}.menu_type',
                    '{{%vendor_item_menu_item}}.menu_item_id',
                    '{{%vendor_item_menu_item}}.menu_item_name',
                    '{{%vendor_item_menu_item}}.menu_item_name_ar',
                    '{{%vendor_item_menu_item}}.price',
                    '{{%customer_cart_menu_item}}.quantity'
                ])
                ->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id')
                ->innerJoin('{{%vendor_item_menu}}', '{{%vendor_item_menu}}.menu_id = {{%customer_cart_menu_item}}.menu_id')
                ->where(['cart_id' => $item['cart_id']])
                ->asArray()
                ->all();

            foreach ($menu_items as $key => $menu_item) {
                $bim = new BookingItemMenu;
                $bim->attributes = $menu_item;
                $bim->booking_item_id = $booking_item->booking_item_id;
                $bim->total = $bim->price * $bim->quantity;
                $bim->save();
            }

            //delivery charge 

            $delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);

            $delivery_charge = $delivery_area->delivery_price;

            //total 

            $total = $booking_item->total + $delivery_charge;

            //commission percentage 

            $vendor = Vendor::findOne($booking->vendor_id);

            if (is_null($vendor->commision) || $vendor->commision == '') {
                $commission_percentage = $vendor->commision;
            } else {
                $commission_percentage = $default_commision;
            }

            $commission_total = $total * ($commission_percentage / 100);

            $booking->commission_percentage = $commission_percentage;
            $booking->commission_total = $commission_total;
            $booking->total_delivery_charge = $delivery_charge;
            $booking->total_without_delivery = $total - $delivery_charge;
            $booking->total_with_delivery = $total;
            $booking->total_vendor = $total - $commission_total;
            $booking->save(false);

            Booking::sendNewBookingEmails($booking);

            $arr_booking_id[] = $booking->booking_id;
        }

        return $arr_booking_id;
    }

    public function getPurchaseDeliveryAddress($address_id)
    {
        $address_model = CustomerAddress::findOne($address_id);

        $purchase_delivery_address = $address_model->address_data.'<br />';
        
        //get address response 
        $address_responses = CustomerAddressResponse::find()
            ->where(['address_id' => $address_id])
            ->all();

        foreach ($address_responses as $response) {
           $purchase_delivery_address .= $response->response_text.'<br />';
        }

        //area 
        $purchase_delivery_address .= $address_model->location->location.'<br />';

        //city 
        $purchase_delivery_address .= $address_model->city->city_name;

        return $purchase_delivery_address;
    }

    public function sendNewBookingEmails($booking) 
    {
        //send to customer

        Yii::$app->mailer->compose([
            "html" => "customer/new-booking"
        ],[
            'user'  => $booking->customer_name,
            'booking' => $booking,
            'vendor' => $booking->vendor
        ])
        ->setFrom(Yii::$app->params['supportEmail'])
        ->setTo($booking->customer_email)
        ->setSubject('New Booking #'.$booking->booking_id)
        ->send();
        
        //send to admin

        Yii::$app->mailer->compose([
            "html" => "customer/new-booking"
        ],[
            'user'  => 'Admin',
            'booking' => $booking,
            'vendor' => $booking->vendor
        ])
        ->setFrom(Yii::$app->params['supportEmail'])
        ->setTo(Yii::$app->params['adminEmail'])
        ->setSubject('New Booking #'.$booking->booking_id)
        ->send();
        
        //send to vendor 

        //get all vendor alert email 
        
        $emails = VendorOrderAlertEmails::find()
            ->where(['vendor_id' => $booking->vendor_id])
            ->all();

        $emails = ArrayHelper::getColumn($emails, 'email_address');

        Yii::$app->mailer->compose([
            "html" => "vendor/new-booking"
        ],[
            'booking' => $booking,
            'vendor' => $booking->vendor
        ])
        ->setFrom(Yii::$app->params['supportEmail'])
        ->setTo($emails)
        ->setSubject('New Booking #'.$booking->booking_id)
        ->send();
    }

    public static function approved($booking) 
    {
        //set expiry 
        $booking->booking_status = Booking::STATUS_ACCEPTED;
        $booking->expired_on = date('Y-m-d H:i:s',strtotime('+2 day')); // set 48 hour expire date
        $booking->save();

        //Send Email to customer

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("customer/request-approved",
            [
                "booking" => $booking,
                "vendor" => $booking->vendor,
                "lnk_payment" => Yii::$app->urlManagerFrontend->createUrl(["payment/index", 'token' => $booking->booking_token])
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($booking->customer_email)
            ->setSubject('Booking request approved!')
            ->send();
    }

    public static function rejected($booking) 
    {
        $booking->booking_status = Booking::STATUS_REJECTED;
        $booking->booking_note = Yii::$app->request->post('booking_note');
        $booking->save(false);

        $items = implode(', ', ArrayHelper::map($booking->bookingItems, 'item_id', 'item_name'));

        //Send Email to customer

        if(Yii::$app->params['notify_customer_request_decline']) 
        {
            Yii::$app->mailer->htmlLayout = 'layouts/empty';

            Yii::$app->mailer->compose("customer/request-rejected",
                [
                    "booking" => $booking,
                    "vendor" => $booking->vendor,
                    "items" => $items,
                    "logo_1" => Url::to("@web/uploads/twb-logo-horiz-white.png", true),
                    "logo_2" => Url::to("@web/uploads/twb-logo-trans.png", true),
                ])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($booking->customer_email)
                ->setSubject('Booking requets rejected!')
                ->send();
        }

        //send to admin 

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("admin/request-rejected",
            [
                "booking" => $booking,
                "vendor" => $booking->vendor,
                "items" => $items,
                "logo_1" => Url::to("@web/uploads/twb-logo-horiz-white.png", true),
                "logo_2" => Url::to("@web/uploads/twb-logo-trans.png", true),
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Booking request rejected!')
            ->send();

    /*
     * cron job for booking expire alert before 1 hour;
     */
    public function bookingBeforeExpireAlert() {

        $q = "SELECT b.customer_id,b.booking_status,b.booking_token,b.booking_id FROM whitebook_booking b ";
        $q .= "WHERE b.expired_on < NOW() - INTERVAL 1 HOUR AND b.notification_status = '0' AND b.booking_status = '1' and b.transaction_id is  NULL";
        $model = Yii::$app->db->createCommand($q);
        $customer = $model->queryAll();
        if ($customer) {
            foreach ($customer as $detail) {
                $customerDetail = Customer::findOne($detail['customer_id']);
                if ($customerDetail) {
                    $message = 'Hello '.$customerDetail->customer_name.' ' .$customerDetail->customer_last_login;
                    $message .= '<br/><br/> Your Approved Booking Item going to Expire in one hour. Please pay pending due before it expire.';
                    $message .= '<br/> Booking Token '.$detail['booking_token'];
                    $message .= '<br/> Booking ID '.$detail['booking_id'];

                    echo Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($customerDetail->customer_email)
                        ->setSubject('Whitebook : Expiring booking token #'.$detail['booking_token'])
                        ->setTextBody($message)
                        ->send();

                }
            }
        }
    }

    /**
     * Send mail to vendor + admin if customer have not paid
     * within 24 hour after booking
     */
    public function bookingExpire(){

        //list all booking with status as pending and placed before 24 hours
        $requests = Booking::find()
            ->where('expired_on < NOW() and transaction_id is NULL')
            ->andWhere([
                'booking_status' => '1'
            ])
            ->all();

        foreach ($requests as $key => $request)
        {
            $vendor = Vendor::findOne($request->vendor_id);

            //get items

            $items = BookingItem::find()
                ->select('item_id, item_name')
                ->where([
                    'booking_id' => $request->booking_id
                ])
                ->asArray()
                ->all();

            $items = implode(', ', ArrayHelper::map($items, 'item_id', 'item_name'));

            // to vendor

            $message = 'Hello '.$vendor->vendor_name.',';
            $message .= '<br/><br/>  The booking is Expired now for '.$items.' on '.date('d/m/Y h:i A').' because customer have not paid within 24 hour.<br />';
            $message .= '<br/> Request Token : '.$request->booking_token;
            $message .= '<br/> Order ID : '.$request->booking_id;

            //get all vendor alert email

            $emails = VendorOrderAlertEmails::find()
                ->where(['vendor_id' => $request->vendor_id])
                ->all();

            $emails = ArrayHelper::getColumn($emails, 'email_address');

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($emails)
                ->setSubject('Booking Expired!')
                ->setHtmlBody($message)
                ->send();

            // to admin

            $message = 'Hello Admin,';
            $message .= '<br/><br/>  The booking is Expired now for '.$items.' on '.date('d/m/Y h:i A').' because customer have not paid within 48 hours.<br />';
            $message .= '<br/> Request Token : '.$request->booking_token;
            $message .= '<br/> Order ID : '.$request->booking_id;

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('Booking Expired!')
                ->setHtmlBody($message)
                ->send();

            //set request status to `Declined`

            $request->request_status = '3';
            $request->request_note = 'Payment not complete withing within 24 hour';
            $request->save();
        }
    }
}
