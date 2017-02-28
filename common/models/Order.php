<?php

namespace common\models;

use Yii;
use yii\web\Request;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\Customer;
use common\models\Suborder;
use common\models\ItemType;
use common\models\VendorItem;
use common\models\OrderStatus;
use common\models\CustomerAddress;
use common\models\VendorItemPricing;
use common\models\SuborderItemPurchase;
use common\models\OrderRequestStatus;

/**
 * This is the model class for table "whitebook_order".
 *
 * @property string $order_id
 * @property string $customer_id
 * @property string $order_total_delivery_charge
 * @property string $order_total_without_delivery
 * @property string $order_total_with_delivery
 * @property string $order_payment_method
 * @property string $order_transaction_id
 * @property string $order_gateway_percentage
 * @property string $order_gateway_fees
 * @property string $order_gateway_total
 * @property string $order_datetime
 * @property string $order_ip_address
 * @property integer $created_by
 * @property string $modified_by
 * @property integer $created_date
 * @property string $modified_date
 * @property string $trash
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
            [['customer_id', 'order_total_delivery_charge', 'order_total_without_delivery', 'order_total_with_delivery', 'order_gateway_percentage', 'order_gateway_total', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime', 'trash'], 'required'],
            [['customer_id', 'created_by'], 'integer'],
            [['order_total_delivery_charge', 'order_total_without_delivery', 'order_total_with_delivery', 'order_gateway_percentage', 'order_gateway_total'], 'number'],
            [['modified_by', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
            [['customerName', 'order_payment_method', 'order_transaction_id', 'order_ip_address'], 'string', 'max' => 128],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
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
        
        if (!$this->order_uid) {
            $this->order_uid = $this->generateUid();
        }
        
        return true;
    }

    public function generateUid()
    {
        $unique = Yii::$app->getSecurity()->generateRandomString(13);

        $exists = Order::findOne([
                'order_uid' => $unique
            ]); ;
        
        if (!empty($exists)) {
            return $this->generateUid();
        }
        
        return $unique;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'customer_id' => 'Customer ID',
            'countryName' => 'Customer',
            'commission' => 'TWB commission (KWD)',
            'order_total_delivery_charge' => 'Delivery Charge (KWD)',
            'order_total_without_delivery' => 'Without Delivery (KWD)',
            'order_total_with_delivery' => 'Total (KWD)',
            'order_payment_method' => 'Order Payment Method',
            'order_transaction_id' => 'Order Transaction ID',
            'order_gateway_percentage' => 'Order Gateway Percentage',
            'order_gateway_total' => 'Gateway Total (KWD)',
            'order_ip_address' => 'Order Ip Address',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'trash' => 'Trash',
        ];
    }

    public function place_order($gateway_name, $gateway_percentage, $gateway_fees, $order_status_id = 0, $transaction_id = '') {

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

            $chanks[$item['vendor_id']][] = $item;
        }
        
        $total = $sub_total + $delivery_charge;

        //total gateway commission 
        $gateway_total = $gateway_percentage * ($total / 100);

        //insert main order
        $order = new Order;
        $order->customer_id = Yii::$app->user->getID();
        $order->order_total_delivery_charge = $delivery_charge;
        $order->order_total_without_delivery = $total - $delivery_charge;
        $order->order_total_with_delivery = $total;
        $order->order_payment_method = $gateway_name;
        $order->order_transaction_id = $transaction_id;
        $order->order_gateway_percentage = $gateway_percentage;
        $order->order_gateway_fees = $gateway_fees;
        $order->order_gateway_total = $gateway_total;
        $order->order_ip_address = Request::getUserIP();
        $order->trash = 'Default';
        $order->save(false);

        //insert suborder 
        foreach($chanks as $vendor_id => $chank) {

            //calculate order total data 
            $total = 0;
            $sub_total = 0;
            $delivery_charge = 0;
            $suborder_commission_total = 0;

            $sub_order = new Suborder;
            $sub_order->order_id = $order->order_id;
            $sub_order->vendor_id = $vendor_id;
            $sub_order->status_id = $order_status_id;
            $sub_order->trash = 'Default';
            $sub_order->save(false);

            //insert items 
            foreach ($chank as $item) {

                //address 
                $address_id = $addresses[$item['cart_id']];

                $item_purchase = new SuborderItemPurchase;
                $item_purchase->suborder_id = $sub_order->suborder_id;
                $item_purchase->working_id = $item['working_id'];
                $item_purchase->item_id  = $item['item_id'];
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
                $delivery_charge += $delivery_area->delivery_price;

                $sub_total += $item_purchase->purchase_total_price;
            }

            $total = $sub_total + $delivery_charge;

            //suborder commission
            $vendor = Vendor::findOne($vendor_id);
            
            if(is_null($vendor->commision) || $vendor->commision == '') {
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
            $sub_order->suborder_commission_total =  $suborder_commission_total;
            $sub_order->suborder_vendor_total =  $total - $suborder_commission_total;
            $sub_order->save(false);

        }//foreach chink 

        return $order->order_id;

    }//END place_order


    public static function placeRequestOrder($gateway_name, $gateway_percentage, $gateway_fees, $order_status_id = 0, $transaction_id = '') {

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
            if ($order->save(false)) {
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
            }

        return $order->order_id;

    }//END place_order

    private function getPurchaseDeliveryAddress($address_id) {

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

    //product count by order id 
    public function itemCount($order_id) {
        return SuborderItemPurchase::find()
            ->where('suborder_id IN (select suborder_id from whitebook_suborder WHERE order_id = '.$order_id.')')
            ->count();
    }

    public function subOrderItems($suborder_id) {
        return SuborderItemPurchase::find()
            ->with('vendoritem')
            ->where(['suborder_id' => $suborder_id])
            ->all();
    }

    public function getCustomer() {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    public function getSubOrder() {
        return $this->hasOne(Suborder::className(), ['order_id' => 'order_id']);
    }

    public function getCustomerName() {
        return $this->customer->customer_name.' '.$this->customer->customer_last_name;
    }

    public function getRequestStatus() {
        return $this->hasOne(OrderRequestStatus::className(), ['order_id' => 'order_id']);
    }

    public function getCommission() {
        
        $result = Suborder::find()
            ->where(['order_id' => $this->order_id])
            ->sum('suborder_commission_total');

        return $result;
    }

    public function sendStatusEmail($suborder_id, $status_id){

        $sub_order = Suborder::findOne($suborder_id);
        $order = $sub_order->order;
        $status = OrderStatus::findOne($status_id)->name;
        $vendor = Vendor::findOne($sub_order->vendor_id);

        //send to customer
        Yii::$app->mailer->compose([
            "html" => "customer/order-status"
        ],[
            'user' => $order->customer->customer_name,
            'order_id' => $order->order_id,
            'sub_order_id' => $suborder_id,
            'vendor' => $vendor->vendor_name,
            'message' => 'Order status changed to "'.$status.'"'
        ])
        ->setFrom(Yii::$app->params['supportEmail'])
        ->setTo($order->customer->customer_email)
        ->setSubject('Sub Order Status changed')
        ->send();
    }

    public function sendNewOrderEmails($order_id) {

        $order = Order::findOne($order_id);

        $suborder = Suborder::find()
            ->where(['order_id' => $order_id])
            ->all();
        
        //send to customer
        Yii::$app->mailer->compose([
            "html" => "customer/new-order"
        ],[
            'user'  => $order->customer->customer_name,
            'order' => $order,
            'suborder' => $suborder
        ])
        ->setFrom(Yii::$app->params['supportEmail'])
        ->setTo($order->customer->customer_email)
        ->setSubject('New Order Placed #'.$order_id)
        ->send();
        
        //send to admin
        Yii::$app->mailer->compose([
            "html" => "customer/new-order"
        ],[
            'user'  => 'Admin',
            'order' => $order,
            'suborder' => $suborder
        ])
        ->setFrom(Yii::$app->params['supportEmail'])
        ->setTo(Yii::$app->params['adminEmail'])
        ->setSubject('New Order Placed #'.$order_id)
        ->send();
        
        //foreach suborder
        foreach ($suborder as $key => $value) {
            
            //get all vendor alert email 
            $emails = VendorOrderAlertEmails::find()
                ->where(['vendor_id' => $value->vendor_id])
                ->all();

            $emails = ArrayHelper::getColumn($emails, 'email_address');

            Yii::$app->mailer->compose([
                "html" => "vendor/new-order"
            ],[
                'model' => $value
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($emails)
            ->setSubject('New Order Placed #'.$value->suborder_id)
            ->send();
        }
    }

    public static function sendOrderPaidEmails($request_id)
    {
        $request = OrderRequestStatus::findOne($request_id);

        $order = Order::findOne($request->order_id);

        $suborder = Suborder::find()
            ->where([
                    'order_id' => $request->order_id,
                    'vendor_id' => $request->vendor_id
                ])
            ->one();

        $customer = Customer::findOne($order->customer_id);
        
        $vendor = Vendor::findOne($request->vendor_id);

        //Send Email to customer

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("customer/order-paid",
            [
                "model" => $suborder,
                "customer" => $customer,
                "vendor" => $vendor
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($customer->customer_email)
            ->setSubject('Order Invoice!')
            ->send();

        //Send Email to vendor

        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        //get all vendor alert email 

        $emails = VendorOrderAlertEmails::find()
            ->where(['vendor_id' => $request->vendor_id])
            ->all();

        $emails = ArrayHelper::map($emails, 'vendor_id', 'email_address');

        Yii::$app->mailer->compose("vendor/order-paid",
            [
                "model" => $suborder,
                "customer" => $customer,
                "vendor" => $vendor
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($emails)
            ->setSubject('Got Order Payment!')
            ->send();
    }

    public static function totalPurchasedItem($item_id= false,$delivery_date = false)
    {
        if ($item_id== false || $delivery_date == false) {
           return false;
        }
        $q = 'select sum(ip.purchase_quantity) as `purchased` from `whitebook_suborder_item_purchase` as `ip`';
        $q .= 'inner join whitebook_suborder so on so.suborder_id = ip.suborder_id ';
        $q .= 'left join `whitebook_order_request_status` as `req` on `req`.`suborder_id` = `ip`.`suborder_id` ';
        $q .= 'where  ip.item_id = "' . $item_id . '" AND ip.trash = "Default" AND so.trash ="Default" ';
        $q .= 'AND so.status_id != 0 AND DATE(ip.purchase_delivery_date) = DATE("' . date('Y-m-d', strtotime($delivery_date)) . '") ';
        $q .= 'AND `req`.`request_status` IN ("Pending","Approved") group by `ip`.`item_id`';

        return Yii::$app->db->createCommand($q)->queryOne();
    }

    public static function totalPendingItem($item_id= false,$delivery_date = false, $item_default_capacity = false)
    {

        if ($item_id== false || $delivery_date == false || $item_default_capacity == false) {
            return 0;
        }

        $capacity_exception = \common\models\VendorItemCapacityException::findOne([
            'item_id' => $item_id,
            'exception_date' => date('Y-m-d', strtotime($delivery_date))
        ]);

        if($capacity_exception && $capacity_exception->exception_capacity) {
            $capacity = $capacity_exception->exception_capacity;
        } else {
            $capacity = $item_default_capacity;
        }
        $purchased_result = self::totalPurchasedItem($item_id,$delivery_date);
        if ($purchased_result) {
            $purchased = $purchased_result['purchased'];
        } else {
            $purchased = 0;
        }
        return $capacity-$purchased;
    }
}
