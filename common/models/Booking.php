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
use common\models\VendorPayment;

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
            [['booking_id', 'vendor_id', 'customer_id', 'notification_status', 'booking_status'], 'integer'],
            [['booking_note'], 'string'],
            [['expired_on', 'created_datetime', 'modified_datetime'], 'safe'],
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
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_EXPIRED => 'Expired'
        ];
    }

    public function getStatusName()
    {
        $statusList = self::statusList();

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
        return $this->hasMany(BookingItem::className(), ['booking_id' => 'booking_id']);
    }

    public function getBookingItemAnswers()
    {
        return $this->hasMany(BookingItemAnswers::className(), ['booking_id' => 'booking_id']);
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
        $area_id = Yii::$app->session->get('deliver-location');
        $cart_delivery_date = date('Y-m-d', strtotime(Yii::$app->session->get('deliver-date')));
        $time_slot = Yii::$app->session->get('event_time');

        //default commision

        $default_commision = Siteinfo::info('commission');

        $items = CustomerCart::items();

        //price chart

        $price_chart = array();

        //check if quantity fall in price chart
        $BasePrice = '';
        foreach ($items as $key => $item) {
            $BasePrice = false;
            $price_chart[$item['item_id']] = [];

            $price = VendorItemPricing::find()
                ->where(['item_id' => $item['item_id'], 'trash' => 'Default'])
                ->andWhere(['<=', 'range_from', $item['cart_quantity']])
                ->andWhere(['>=', 'range_to', $item['cart_quantity']])
                ->orderBy('pricing_price_per_unit DESC')
                ->one();

            if ($price) {
                $price_chart[$item['item_id']]['unit_price'] = $price->pricing_price_per_unit;
            } else{
                $price_chart[$item['item_id']]['unit_price'] = $item['item_price_per_unit'];
            }

            $price_chart[$item['item_id']]['base_price'] = ($item['item']['item_base_price']) ? $item['item']['item_base_price'] : 0.0;

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

        //address
        
        $address_id = Yii::$app->session->get('address_id');
        
        $address = Booking::getPurchaseDeliveryAddress($address_id);
    
        $arr_booking_id = [];
        
        $arr_booking = [];

        foreach ($items as $item) {

            if ($item['item']['included_quantity'] > 0) {
                $min_quantity_to_order = $item['item']['included_quantity'];
            } else {
                $min_quantity_to_order = 1;
            }

            $actual_item_quantity = $item['cart_quantity'] - $min_quantity_to_order;


            $total = $price_chart[$item['item_id']]['base_price']; // base price change
            $total += ($price_chart[$item['item_id']]['unit_price'] * $actual_item_quantity) + $price_chart[$item['item_id']]['menu_price'];
            $booking = new Booking;
            $booking->vendor_id = $item['vendor_id'];
            $booking->customer_id = $customer_id;
            $booking->customer_name = $customer_name;
            $booking->customer_lastname = $customer_lastname;
            $booking->customer_email = $customer_email;
            $booking->booking_status = Booking::STATUS_PENDING;
            $booking->customer_mobile = $customer_mobile;
            $booking->ip_address = Request::getUserIP();
            $booking->save(false);

            $booking_item = new BookingItem;
            $booking_item->booking_id = $booking->booking_id;
            $booking_item->item_id = $item['item_id'];
            $booking_item->item_name = $item['item_name'];
            $booking_item->item_name_ar = $item['item_name_ar'];

            $booking_item->timeslot = $time_slot;
            $booking_item->area_id = $area_id;
            $booking_item->delivery_date = $cart_delivery_date;
            
            $booking_item->address_id = $address_id;
            $booking_item->delivery_address = $address;
            
            $booking_item->item_base_price = ($price_chart[$item['item_id']]['base_price']) ? $price_chart[$item['item_id']]['base_price'] : 0.000;
            $booking_item->price = $price_chart[$item['item_id']]['unit_price'];
            $booking_item->quantity = $item['cart_quantity'];
            $booking_item->total = $total;
            //$booking_item->total = ($price_chart[$item['item_id']]['unit_price'] * $item['cart_quantity']) + $price_chart[$item['item_id']]['menu_price'];
            $booking_item->female_service = $item['female_service'];
            $booking_item->special_request = $item['special_request'];
            
            $booking_item->save(false);

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
                $commission_percentage = $default_commision;
            } else {
                $commission_percentage = $vendor->commision;
            }

            $commission_total = $total * ($commission_percentage / 100);

            $booking->commission_percentage = $commission_percentage;
            $booking->commission_total = $commission_total;
            $booking->total_delivery_charge = $delivery_charge;
            $booking->total_without_delivery = $total - $delivery_charge;
            $booking->total_with_delivery = $total;
            $booking->total_vendor = $total - $commission_total;
            $booking->save(false);

            $arr_booking[] = $booking;

            $arr_booking_id[] = $booking->booking_id;

            BookingItemAnswers::saveItemAnswers($item['cart_id'],$booking->booking_id); //Saving cart item answer
        }

        Booking::sendNewBookingEmails($arr_booking);

        return $arr_booking_id;
    }

    public function getPurchaseDeliveryAddress($address_id)
    {
        $model = CustomerAddress::findOne($address_id);

        if(!$model) 
            return null;

        $address = '';

        $address_responses = CustomerAddressResponse::find()
            ->where(['address_id' => $address_id])
            ->all();
        foreach ($address_responses as $response) {
            if($response->response_text) {
                $address .= '<strong>'.$response->addressQuestion->question. ':</strong> ' .$response->response_text.'<br />';
            }
        }

        if($model->address_data) {
            $address .= '<strong>Address Data: </strong>'.$model->address_data.'<br />';
        }
        
        if($model->location) {
            $address .= '<strong>Area:</strong> '.$model->location->location.'<br />';
        }
        
        if($model->city) {
            $address .= '<strong>City:</strong> '.$model->city->city_name.'<br />';
        }        

        return $address;
    }

    public function sendNewBookingEmails($arr_booking) 
    {
        //send to customer

        Yii::$app->mailer->compose([
            "html" => "customer/new-booking"
        ],[
            'user'  => $arr_booking[0]->customer_name,
            'arr_booking' => $arr_booking
        ])
        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
        ->setTo($arr_booking[0]->customer_email)
        ->setSubject('New Booking #'.$arr_booking[0]->booking_id)
        ->send();

        
        //send to admin

        Yii::$app->mailer->compose([
            "html" => "customer/new-booking"
        ],[
            'user'  => 'Admin',
            'arr_booking' => $arr_booking,
        ])
        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
        ->setTo(Yii::$app->params['adminEmail'])
        ->setSubject('New Booking #'.$arr_booking[0]->booking_id)
        ->send();
        
        //send to vendor 

        $arr_vendor_booking = [];

        foreach ($arr_booking as $key => $value) 
        {   
            if(!isset($arr_vendor_booking[$value->vendor_id])) 
                $arr_vendor_booking[$value->vendor_id] = [];

            $arr_vendor_booking[$value->vendor_id][] = $value;
        }

        foreach ($arr_vendor_booking as $key => $arr_booking) 
        {   
            //get all vendor alert email 

            if (Vendor::vendorManageBy($arr_booking[0]->vendor_id) == 'vendor') {
                $emails = VendorOrderAlertEmails::find()
                    ->where(['vendor_id' => $arr_booking[0]->vendor_id])
                    ->all();

                $emails = ArrayHelper::getColumn($emails, 'email_address');
            } else {
                $emails = Yii::$app->params['BookingEmail'];
            }

            Yii::$app->mailer->compose([
                "html" => "vendor/new-booking"
            ],[
                'arr_booking' => $arr_booking,
                'vendor' => $arr_booking[0]->vendor
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo($emails)
            ->setSubject('New Booking #'.$arr_booking[0]->booking_id)
            ->send();
        }
    }

    public static function approved($booking) 
    {
        //set expiry 
        $booking->booking_status = Booking::STATUS_ACCEPTED;
        $booking->expired_on = date('Y-m-d H:i:s',strtotime('+2 day')); // set 48 hour expire date
        $booking->save(false);

        //Send Email to customer

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("customer/booking-approved",
            [
                "booking" => $booking,
                "vendor" => $booking->vendor,
                "lnk_payment" => Yii::$app->urlManagerFrontend->createUrl(["payment/index", 'token' => $booking->booking_token])
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo($booking->customer_email)
            ->setSubject('Booking ID #'.$booking->booking_id.' request approved!')
            ->send();
    }

    public static function rejected($booking)
    {
        $booking->booking_status = Booking::STATUS_REJECTED;
        $booking->booking_note = Yii::$app->request->post('booking_note');
        $booking->save(false);

        $items = implode(', ', ArrayHelper::map($booking->bookingItems, 'item_id', 'item_name'));

        //Send Email to customer

        if (Yii::$app->params['notify_customer_request_decline']) {
            Yii::$app->mailer->htmlLayout = 'layouts/empty';

            Yii::$app->mailer->compose("customer/booking-rejected",
                [
                    "booking" => $booking,
                    "vendor" => $booking->vendor,
                    "items" => $items,
                    "logo_1" => Url::to("@web/uploads/twb-logo-horiz-white.png", true),
                    "logo_2" => Url::to("@web/uploads/twb-logo-trans.png", true),
                ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
                ->setTo($booking->customer_email)
                ->setSubject('Booking ID #'.$booking->booking_id.' request rejected!')
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
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Booking ID #'.$booking->booking_id.' request rejected!')
            ->send();


    }
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
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
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
            $message .= '<br/><br/>  The booking is Expired now for '.$items.' on '.date('d/m/Y h:i A').' because customer have not paid within 48 hour.<br />';
            $message .= '<br/> Request Token : '.$request->booking_token;
            $message .= '<br/> Booking ID : '.$request->booking_id;

            //get all vendor alert email

            if (Vendor::vendorManageBy($request->vendor_id) == 'vendor') {
                $emails = VendorOrderAlertEmails::find()
                    ->where(['vendor_id' => $request->vendor_id])
                    ->all();

                $emails = ArrayHelper::getColumn($emails, 'email_address');
            } else {
                $emails = Yii::$app->params['BookingEmail'];
            }

            Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
                ->setTo($emails)
                ->setSubject('Booking ID #'.$request->booking_id.' Expired!')
                ->setHtmlBody($message)
                ->send();

            // to admin

            $message = 'Hello Admin,';
            $message .= '<br/><br/>  The booking is Expired now for '.$items.' on '.date('d/m/Y h:i A').' because customer have not paid within 48 hours.<br />';
            $message .= '<br/> Request Token : '.$request->booking_token;
            $message .= '<br/> Booking ID : '.$request->booking_id;

            Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('Booking ID #'.$request->booking_id.' Expired!')
                ->setHtmlBody($message)
                ->send();

            //set request status to `Declined`

            $request->request_status = '3';
            $request->request_note = 'Payment not complete withing within 48 hour';
            $request->save();
        }
    }

    public function setExpiredOn($model) {
        if (
            ($model->oldAttributes['booking_status'] != $model->booking_status) &&
            $model->booking_status == self::STATUS_ACCEPTED
        )  {
            $model->expired_on = date('Y-m-d H:i:s',strtotime('+2 Day'));
        }
    }

    public static function getReportQuery($data = array())
    {    
        $query = self::find()
          ->select('
              MIN(created_datetime) AS date_start, 
              MAX(created_datetime) AS date_end, 
              COUNT(*) AS `count`,
              SUM(commission_total) AS `commission`,
          ')
          ->where(['booking_status' => self::STATUS_ACCEPTED]);

        if (!empty($data['vendor_id'])) {
          $query->andWhere('vendor_id = ' . $data['vendor_id']);
        } 

        if (!empty($data['date_start'])) {
          $query->andWhere("DATE(created_datetime) >= '" . $data['date_start'] . "'");
        }

        if (!empty($data['date_end'])) {
          $query->andWhere("DATE(created_datetime) <= '" . $data['date_end'] . "'");
        }

        if (!empty($data['group_by'])) {
          $group = $data['group_by'];
        } else {
          $group = 'day';
        }

        switch($group) {
          case 'day';
            $query->groupBy("YEAR(created_datetime), MONTH(created_datetime), DAY(created_datetime)");
            break;
          default:
          case 'week':
            $query->groupBy("YEAR(created_datetime), WEEK(created_datetime)");
            break;
          case 'month':
            $query->groupBy("YEAR(created_datetime), MONTH(created_datetime)");
            break;
          case 'year':
            $query->groupBy("YEAR(created_datetime)");
            break;
        }

        $query->orderBy("created_datetime DESC");

        return $query;
    }

    public static function sendBookingPaidEmails($booking_id)
    {
        $booking = Booking::findOne($booking_id);

        //Send Email to customer

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("customer/booking-paid",
            [
                "model" => $booking,
                "vendor" => $booking->vendor
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo($booking->customer_email)
            ->setSubject('Booking ID #'.$booking_id.' Invoice!')
            ->send();

        //Send Email to vendor

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        //get all vendor alert email 

        if (Vendor::vendorManageBy($booking->vendor_id) == 'vendor') {
            $emails = VendorOrderAlertEmails::find()
                ->where(['vendor_id' => $booking->vendor_id])
                ->all();

            $emails = ArrayHelper::getColumn($emails, 'email_address');
        } else {
            $emails = Yii::$app->params['BookingEmail'];
        }

        Yii::$app->mailer->compose("vendor/booking-paid",
            [
                "model" => $booking,
                "vendor" => $booking->vendor
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo($emails)
            ->setSubject('Got Booking Payment for ID #'.$booking_id)
            ->send();
    }

    public static function totalPurchasedItem($item_id = false, $delivery_date = false)
    {
        if ($item_id== false || $delivery_date == false) {
            return false;
        }

        $q = 'select sum(bi.quantity) as `purchased` from `whitebook_booking_item` as `bi`';
        $q .= ' inner join whitebook_booking b on b.booking_id = bi.booking_id where bi.item_id = '.$item_id;
        $q .= ' AND b.booking_status IN (0,1) AND DATE(bi.delivery_date) = DATE("' . date('Y-m-d', strtotime($delivery_date)) . '")';
        $q .= ' group by `bi`.`item_id`';

        return Yii::$app->db->createCommand($q)->queryOne();
    }

    public static function addPayment($booking) 
    {
        $payment = new VendorPayment;
        $payment->vendor_id = $booking->vendor_id;
        $payment->booking_id = $booking->booking_id;
        $payment->type = VendorPayment::TYPE_ORDER;
        $payment->amount = $booking->total_vendor;
        $payment->description = 'Booking #'.$booking->booking_id.' got paid.';
        
        if(!$payment->save())
        {
            print_r($payment->getErrors());
        }
    }

    /**
     * @inheritdoc
     * @return query\BookingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\BookingQuery(get_called_class());
    }
}
