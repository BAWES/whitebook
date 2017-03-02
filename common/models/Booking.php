<?php

namespace common\models;

use Yii;

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
            [['expired_on', 'created_datetime', 'modified_datetime'], 'safe'],
            [['commission_percentage', 'commission_total', 'gateway_percentage', 'gateway_fees', 'gateway_total', 'total_delivery_charge', 'total_without_delivery', 'total_with_delivery', 'total _vendor'], 'number'],
            [['booking_token'], 'string', 'max' => 13],
            [['customer_name', 'customer_lastname', 'customer_email', 'payment_method', 'transaction_id'], 'string', 'max' => 100],
            [['customer_mobile'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 128],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }

    public static function statusList()
    {
        return [
            STATUS_PENDING => 'Pending',
            STATUS_ACCEPTED => 'Accepted',
            STATUS_REJECTED => 'Rejected'
        ];
    }

    public static function getStatusName() 
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
        return $this->hasMany(BookingItem::className(), ['booking_id' => 'booking_id']);
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

        //make chunks of item by vendor id

        $chanks = [];

        $total = $sub_total = $delivery_charge = 0;

        foreach ($items as $item) {

            $delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);
            $delivery_charge += $delivery_area->delivery_price;

            $sub_total += ($price_chart[$item['item_id']]['unit_price'] * $item['cart_quantity']) + $price_chart[$item['item_id']]['menu_price'];
            $total = $sub_total + $delivery_charge;
        }

        //insert main order
        $order = new Order;
        $order->customer_id = Yii::$app->user->getID();
        $order->order_total_delivery_charge = $delivery_charge;
        $order->order_total_without_delivery = $total - $delivery_charge;
        $order->order_total_with_delivery = $total;
        $order->order_ip_address = Request::getUserIP();
        $order->trash = 'Default';
        $order->save(false);

        foreach ($items as $item) {

            $sub_order = new Suborder;
            $sub_order->order_id = $order->order_id;
            $sub_order->vendor_id = $item['vendor_id'];
            $sub_order->status_id = 8; // Pending
            $sub_order->trash = 'Default';
            $sub_order->suborder_payment_method = '-';
            $sub_order->suborder_transaction_id = $transaction_id;
            $sub_order->suborder_gateway_percentage = 0.0;
            $sub_order->suborder_gateway_fees = 0.0;
            $sub_order->suborder_gateway_total = 0;

            if ($sub_order->save(false)) {

                $request = new OrderRequestStatus();
                $request->order_id = $order->order_id;
                $request->suborder_id = $sub_order->suborder_id;
                $request->vendor_id = $item['vendor_id'];
                $request->request_status = 'Pending';
                $request->save(false);

                //calculate order total data
                $total = 0;
                $sub_total = 0;
                $delivery_charge = 0;
                $suborder_commission_total = 0;

                //address
                $address_id = $addresses[$item['cart_id']];

                $item_purchase = new SuborderItemPurchase;
                $item_purchase->suborder_id = $sub_order->suborder_id;
                $item_purchase->time_slot = $item['time_slot'];
                $item_purchase->item_id = $item['item_id'];
                $item_purchase->area_id = $item['area_id'];
                $item_purchase->address_id = $address_id;
                $item_purchase->purchase_delivery_address = Order::getPurchaseDeliveryAddress($address_id);
                $item_purchase->purchase_delivery_date = $item['cart_delivery_date'];
                $item_purchase->purchase_price_per_unit = $price_chart[$item['item_id']]['unit_price'];
                $item_purchase->purchase_customization_price_per_unit = 0;
                $item_purchase->purchase_quantity = $item['cart_quantity'];
                $item_purchase->purchase_total_price = ($price_chart[$item['item_id']]['unit_price'] * $item['cart_quantity']) + $price_chart[$item['item_id']]['menu_price'];
                $item_purchase->female_service = $item['female_service'];
                $item_purchase->special_request = $item['special_request'];
                $item_purchase->trash = 'Default';
                $item_purchase->save(false);

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
                    $soim = new SuborderItemMenu;
                    $soim->attributes = $menu_item;
                    $soim->purchase_id = $item_purchase->purchase_id;
                    $soim->total = $soim->price * $soim->quantity;
                    $soim->save();
                }

                //sub order total data

                $delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);
                $delivery_charge = $delivery_area->delivery_price;

                $sub_total = $item_purchase->purchase_total_price;
            }

            $total = $sub_total + $delivery_charge;

            //suborder commission
            $vendor = Vendor::findOne($sub_order->vendor_id);

            if (is_null($vendor->commision) || $vendor->commision == '') {
                $suborder_commission_percentage = $vendor->commision;
            } else {
                $suborder_commission_percentage = $default_commision;
            }

            $suborder_commission_total = $total * ($suborder_commission_percentage / 100);

            //update sub order total
            $sub_order->suborder_delivery_charge = $delivery_charge;
            $sub_order->suborder_total_without_delivery = $total - $delivery_charge;
            $sub_order->suborder_total_with_delivery = $total;
            $sub_order->suborder_commission_percentage = $suborder_commission_percentage;
            $sub_order->suborder_commission_total = $suborder_commission_total;
            $sub_order->suborder_vendor_total = $total - $suborder_commission_total;
            $sub_order->save(false);
        }

        Order::sendNewOrderEmails($order->order_id);

        return $order->order_id;

    }
}
