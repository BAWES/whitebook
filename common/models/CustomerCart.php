<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use common\components\CFormatter;

/**
 * This is the model class for table "whitebook_customer_cart".
 *
 * @property string $cart_id
 * @property string $customer_id
 * @property string $item_id
 * @property string $area_id
 * @property string $time_slot
 * @property string $cart_delivery_date
 * @property string $cart_customization_price_per_unit
 * @property integer $cart_quantity
 * @property string $cart_datetime_added
 * @property string $cart_valid
 * @property string $cart_session_id
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class CustomerCart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_customer_cart';
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
    public function rules()
    {
        return [
            [['item_id', 'cart_customization_price_per_unit', 'cart_quantity', 'cart_datetime_added'], 'required'],
            [['customer_id', 'item_id', 'cart_quantity', 'created_by','modified_by'], 'integer'],

            [['cart_datetime_added', 'created_datetime', 'modified_datetime', 'female_service', 'special_request'], 'safe'],

            [['cart_customization_price_per_unit'], 'number'],
            ['cart_quantity', 'compare', 'compareValue' => 0, 'operator' => '>'],
            [['cart_valid', 'trash', 'cart_session_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cart_id' => 'Cart ID',
            'customer_id' => Yii::t('frontend', 'Customer'),
            'item_id' => Yii::t('frontend', 'Item'),
            'cart_customization_price_per_unit' => Yii::t('frontend', 'Cart Customization Price Per Unit'),
            'cart_quantity' => Yii::t('frontend', 'Quantity'),
            'cart_datetime_added' => Yii::t('frontend', 'Cart Datetime Added'),
            'cart_valid' => Yii::t('frontend', 'Cart Valid'),
            'cart_session_id' => Yii::t('frontend', 'Cart Session ID'),
            'created_by' => Yii::t('frontend', 'Created By'),
            'modified_by' => Yii::t('frontend', 'Modified By'),
            'created_datetime' => Yii::t('frontend', 'Created Datetime'),
            'modified_datetime' => Yii::t('frontend', 'Modified Datetime'),
            'trash' => Yii::t('frontend', 'Trash')
        ];
    }

    public function getArea()
    {
        return $this->hasOne(Location::className(), ['id' => 'area_id']);
    }

    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    public function getImage()
    {
        return $this->hasOne(Image::className(), ['item_id' => 'item_id']);
    }

    public function getItemAnswers()
    {
        return $this->hasOne(CustomerCartItemQuestionAnswer::className(), ['cart_id' => 'cart_id']);
    }

    public function validate_item($data, $valid_for_cart_item = false) {

        $errors = [];

        $item = VendorItem::findOne($data['item_id']);

        if(!$item) {
            $errors['warning'] = [
                Yii::t('frontend', 'Item not available for sell!')
            ];
            return $errors;
        }

        $vendor_id = $item->vendor_id;

        //get item type
        $item_type = ItemType::findOne($item->type_id);

        if($item_type) {
            $item_type_name = $item_type->type_name;
        } else {
            $item_type_name = 'Product';
        }

        //check if same item with same date available in cart
        //
            $query = CustomerCart::find();
            $query->where([
                    'item_id' => $data['item_id'],
                    'cart_valid' => 'yes',
                    'trash' => 'Default'
                ]);

            if (Yii::$app->user->getId()) {
                $query->andWhere(['customer_id'=>Yii::$app->user->getId()]);
            } else {
                $query->andWhere(['cart_session_id'=>Customer::currentUser()]);
            }

            $in_cart = $query->sum('cart_quantity');

        /*
            Check if deliery availabel in selected area
        */
        if(!empty($data['area_id'])) {


            if ($data['area_id'] != '') {
                $deliverlocation = $data['area_id'];
                if (is_numeric($deliverlocation)) {
                    $location = $deliverlocation;
                } else {
                    $end = strlen($deliverlocation);
                    $from = strpos($deliverlocation, '_') + 1;
                    $address_id = substr($deliverlocation, $from, $end);
                    $address = CustomerAddress::findOne($address_id);

                    if($address)
                        $location = $address->area_id;
                }
            }

            if(!empty($location))
            {
                $delivery_area = CustomerCart::checkLocation($location, $vendor_id);
            }
            else
            {
                $delivery_area = false;
            }

            if(!$delivery_area) {

                $errors['area_id'] = [
                    Yii::t('frontend', 'Delivery not available on selected area!')
                ];
            }
        }

        //item_minimum_quantity_to_order

        if($data['quantity'] < $item->item_minimum_quantity_to_order) {

            $errors['cart_quantity'][] = [

                Yii::t('yii', '{attribute} must be greater than or equal to "{compareValueOrAttribute}".', [
                    'attribute' => Yii::t('frontend', 'Quantity'),
                    'compareValueOrAttribute' => $item->item_minimum_quantity_to_order
                ])
            ];
        }

        if(!$data['delivery_date'])  {
            $errors['cart_delivery_date'][] = Yii::t('frontend','Select Delivery date!');

            /**
             * all validation after this loop require delivery date, so
             * we returning delivery date require error
             */
            return $errors;
        }

        // to check with old delivery date

        if ($data['delivery_date'] && strtotime($data['delivery_date']) < strtotime(date('Y-m-d'))) {
            $errors['cart_delivery_date'][] = Yii::t('frontend','Invalid delivery date');
        }

        //get timeslot
        if (empty($data['time_slot']))
        {
            $errors['time_slot'][] = Yii::t('frontend', 'Select Delivery time!');
        }

        //check if time available

        if(!empty($data['time_slot']) && !empty($data['delivery_date']))
        {
            $vendor_timeslot = VendorWorkingTiming::find()
                ->where([
                        'vendor_id' => $item->vendor_id,
                        'working_day' => date("l", strtotime($data['delivery_date'])),
                        'trash' => 'Default'
                    ])
                ->all();

            $time = strtotime($data['time_slot']);

            $time_available = false;

            foreach ($vendor_timeslot as $key => $value) {

                if($value->working_end_time == '00:00:00') {
                    $value->working_end_time = '24:00:00';
                }

                $start_time = strtotime($value->working_start_time);
                $end_time = strtotime($value->working_end_time);

                if($time >= $start_time && $time <= $end_time) {
                    $time_available = true;
                }
            }

            if(!$time_available) {
                $errors['time_slot'][] = Yii::t('frontend', 'Delivery time not available!');
            }
        }

        // delivery datetime < current time + notice period hours

        if($item->notice_period_type == 'Hour' && !empty($data['time_slot']))
        {
            $min_delivery_time = strtotime('+'.$item->item_how_long_to_make.' hours');
            $delivery_time = strtotime($data['delivery_date'].' '.$data['time_slot']);

            if($delivery_time < $min_delivery_time)
            {
                $errors['cart_delivery_date'][] = Yii::t('frontend', 'Item notice period {count} hour(s)!', [
                        'count' => $item->item_how_long_to_make
                    ]);
            }
        }


        if($item->notice_period_type == 'Day' && !empty($data['delivery_date']))
        {
            //compare timestamp of date

            $min_delivery_time = strtotime(date('Y-m-d', strtotime('+'.$item->item_how_long_to_make.' days')));
            $delivery_time = strtotime(date('Y-m-d', strtotime($data['delivery_date'])));

            if($delivery_time < $min_delivery_time)
            {
                $errors['cart_delivery_date'][] = Yii::t('frontend', 'Item notice period {count} day(s)!', [
                        'count' => $item->item_how_long_to_make
                    ]);
            }
        }

        //-------------- Start Item Capacity -----------------//
        //default capacity is how many of it they can process per day

        //1) get capacity exception for selected date
        $capacity_exception = VendorItemCapacityException::findOne([
            'item_id' => $data['item_id'],
            'exception_date' => date('Y-m-d', strtotime($data['delivery_date']))
        ]);

        if($capacity_exception && $capacity_exception->exception_capacity) {
            $capacity = $capacity_exception->exception_capacity;
        } else {
            $capacity = $item->item_default_capacity;
        }

        //2) get no of item purchased for selected date

        $purchased_result = Booking::totalPurchasedItem($data['item_id'],$data['delivery_date']);

        if($purchased_result) {
            $purchased = $purchased_result['purchased'];
        } else {
            $purchased = 0;
        }

        //3) campare capacity

        if($valid_for_cart_item && ($purchased + $in_cart) > $capacity) {

            $no_of_available = $capacity - $purchased;

            $errors['cart_quantity'][] = Yii::t('frontend', 'Item is Out of stock');

            $errors['cart_quantity_remain']= ($purchased + $in_cart) - $capacity;
//            $errors['cart_quantity'][] = [
//                Yii::t('frontend', 'Max item available for selected date is "{no_of_available}".', [
//                   'no_of_available' => $no_of_available
//                ])
//            ];
        }

        //validate to add product to cart

        if(!$valid_for_cart_item && ($data['quantity'] + $purchased + $in_cart) > $capacity) {

            $no_of_available = $capacity - $purchased - $in_cart;
            $errors['cart_quantity_remain'] = $no_of_available;
//            $errors['cart_quantity'][] = [
//                Yii::t('frontend', 'Max item available for selected date is "{no_of_available}".', [
//                   'no_of_available' => $no_of_available
//                ])
//            ];

            if($in_cart > 0)
                $errors['cart_quantity'][] = Yii::t('frontend', 'No more item available, already added available in cart');
            else         
                $errors['cart_quantity'][] = Yii::t('frontend', 'Item is Out of stock');

            if($errors)
                return $errors;
        }

        //-------------- END Item Capacity -----------------//

        //current date should not in blocked date
        $block_date = BlockedDate::findOne([
            'vendor_id' => $vendor_id,
            'block_date' => date('Y-m-d', strtotime($data['delivery_date']))
        ]);

        if($block_date) {
            $errors['cart_delivery_date'][] = Yii::t('frontend', 'Item is not available on selected date');
        }

        //day should not in week off
        $blocked_days = explode(',', Vendor::findOne($vendor_id)->blocked_days);
        $day = date('N', strtotime($data['delivery_date']));//7-sunday, 1-monday

        if(!$block_date && in_array($day, $blocked_days)) {
            $errors['cart_delivery_date'][] = Yii::t('frontend', 'Item is not available on selected date');
        }

        //item total

        $price_chart = VendorItemPricing::find()
            ->where(['item_id' => $item['item_id'], 'trash' => 'Default'])
            ->andWhere(['<=', 'range_from', $data['quantity']])
            ->andWhere(['>=', 'range_to', $data['quantity']])
            ->orderBy('pricing_price_per_unit DESC')
            ->one();

        if ($price_chart) {
            $unit_price = $price_chart->pricing_price_per_unit;
        } else {
            $unit_price = $item['item_price_per_unit'];
        }

        if ($item['included_quantity'] > 0) {
            $min_quantity_to_order = $item['included_quantity'];
        } else {
            $min_quantity_to_order = 1;
        }

        $actual_item_quantity = $data['quantity'] - $min_quantity_to_order;

        $total = $item->item_base_price + ($unit_price * $actual_item_quantity);

        //get quantity ordered per menu

        $menu_qty_ordered = [];

        if(!isset($data['menu_item'])) {
            $data['menu_item'] = [];
        }

        foreach ($data['menu_item'] as $key => $value) {

            $mi = VendorItemMenuItem::findOne($key);

            /* get quantity selected per menu to validate */

            if(isset($menu_qty_ordered[$mi->menu_id])) {
                $menu_qty_ordered[$mi->menu_id] = $value + $menu_qty_ordered[$mi->menu_id];
            } else {
                $menu_qty_ordered[$mi->menu_id] = $value;
            }

            $total += $mi->price * $value;// * $data['quantity'];
        }

        //item menu

        $item_menues = VendorItemMenu::findAll(['item_id' => $item->item_id]);

        //menu quantity validation

        foreach ($item_menues as $key => $menu) {

            $max = $menu->max_quantity;
            $min = $menu->min_quantity;

            if(isset($menu_qty_ordered[$menu->menu_id])) {
                $qty_ordered = $menu_qty_ordered[$menu->menu_id];
            }else{
                $qty_ordered = 0;
            }

            if(Yii::$app->language == 'en') {
                $menu_name = $menu->menu_name;
            }else{
                $menu_name = $menu->menu_name_ar;
            }

            if($max && $qty_ordered > $max) {
                $errors['menu_'.$menu->menu_id][] = Yii::t(
                    'frontend',
                    'Quantity must be less than or equal to {qty} in "{menu_name}"', [
                        'qty' => $max,
                        'menu_name' => $menu_name
                    ]
                );
            }

            if($qty_ordered < $min) {
                $errors['menu_'.$menu->menu_id][] = Yii::t(
                    'frontend',
                    'Quantity must be greater than or equal to {qty} in "{menu_name}"', [
                        'qty' => $min,
                        'menu_name' => $menu_name
                    ]
                );
            }
        }

        $vendorQuestion = VendorItemQuestion::findAll(['item_id'=>$data['item_id'], 'required'=>1]);
        if ($vendorQuestion) {
            foreach($vendorQuestion as $question) {
                  if (isset($data['answer'][$question->item_question_id]) && $data['answer'][$question->item_question_id] == "") {
                    $errors['question-'.$question->item_question_id][] =
                        Yii::t('frontend', 'Please provide detail of above question');
                }
            }
        }

        //min_order_amount

        if($total < $item->min_order_amount) {
            $errors['cart_quantity'][] = Yii::t(
                'frontend',
                'Min. order amount "{min_order_amount}".',
                [
                   'min_order_amount' => CFormatter::format($item->min_order_amount)
                ]);
        }

        return $errors;
    }

    //return customer items
    public static function items() {

        $query = CustomerCart::find()
            ->select('
                {{%customer_cart}}.*,
                {{%image}}.image_path,
                {{%vendor_item}}.item_price_per_unit,
                {{%vendor_item}}.type_id,
                {{%vendor_item}}.slug,
                {{%vendor_item}}.vendor_id,
                {{%vendor_item}}.item_name,
                {{%vendor_item}}.item_name_ar,
                {{%vendor_item}}.have_female_service,
                {{%vendor_item}}.allow_special_request'
            )
            ->joinWith('item')
            ->joinWith('image')
            ->where([
                '{{%customer_cart}}.cart_valid' => 'yes',
                '{{%customer_cart}}.trash' => 'Default',
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.item_approved' => 'Yes',
            ]);

            if (Yii::$app->user->getId()) {
                $query->andWhere(['{{%customer_cart}}.customer_id'=>Yii::$app->user->getId()]);
            } else {
                $query->andWhere(['{{%customer_cart}}.cart_session_id'=>Customer::currentUser()]);
            }

        $items = $query->asArray()
            ->all();

        return $items;
    }

    public static function item_count() {

        $query = CustomerCart::find()
            ->joinWith('item')
            ->where([
                '{{%customer_cart}}.customer_id' => Yii::$app->user->getId(),
                '{{%customer_cart}}.cart_valid' => 'yes',
                '{{%customer_cart}}.trash' => 'Default',
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.item_approved' => 'Yes',
            ]);

        if (Yii::$app->user->getId()) {
            $query->andWhere(['{{%customer_cart}}.customer_id' => Yii::$app->user->getId()]);
        } else {
            $query->andWhere(['{{%customer_cart}}.cart_session_id' => Customer::currentUser()]);
        }

        return $query->count();
    }

    /*
        Get location info
    */
    public static function geLocation($area_id, $vendor_id){

        $result = VendorLocation::find()
            ->joinWith('location')
            ->where([
                '{{%location}}.status' => 'Active',
                '{{%location}}.trash' => 'Default',
                '{{%vendor_location}}.vendor_id' => $vendor_id,
                '{{%vendor_location}}.area_id' => $area_id])
            ->one();

        return $result;
    }

    /*
        Check if delivery availble on selected area
    */
    public static function checkLocation($area_id, $vendor_id){

        $result = VendorLocation::find()
            ->joinWith('location')
            ->where([
                '{{%location}}.status' => 'Active',
                '{{%location}}.trash' => 'Default',
                '{{%vendor_location}}.vendor_id' => $vendor_id,
                '{{%vendor_location}}.area_id' => $area_id])
            ->count();

        return $result;
    }

    public static function customerAddress(){

        if(Yii::$app->user->isGuest)
        {
            return [];
        }

        $area_id  = Yii::$app->session->get('delivery-location');

        if(!$area_id)
        {
            return [];
        }

        $result = CustomerAddress::find()
            ->joinWith('location')
            ->joinWith('city')
            ->where([
                '{{%customer_address}}.customer_id' => Yii::$app->user->getId(),
                '{{%customer_address}}.trash' => 'Default',
                '{{%location}}.id' => $area_id,
                '{{%location}}.status' => 'Active',
                '{{%location}}.trash' => 'Default',
                '{{%city}}.status' => 'Active',
                '{{%city}}.trash' => 'Default'])
            ->asArray()
            ->all();

        return $result;
    }

    public static function getAddressData($address_id)
    {
        return Booking::getPurchaseDeliveryAddress($address_id);
    }

    public static function getItemVendor($item_id) {
        return VendorItem::findOne(['item_id'=>$item_id])->vendor;
    }

    public function getVendorDetail($vendor_id){
        return Vendor::findOne($vendor_id);
    }


    public static function validationProductAvailable(
        $item_id,
        $area_id,
        $delivery_date,
        $time_slot,
        $menu_item,
        $quantity
    ) {

        if(empty($item_id)) {
            $json['error'] = Yii::t('frontend', 'Item ID require!');

            return $json;
        }

        if(empty($area_id)) {
            $json['error'] = Yii::t('frontend', 'Invalid Area ID!');

            return $json;
        }

        $item = VendorItem::findOne($item_id);

        if (!$item) {
            $json['error'] = Yii::t('frontend', 'Item not available for sell!');

            return $json;
        }

        $vendor_id = $item->vendor_id;

        /*
            Check if deliery availabel in selected area
        */
        if (!empty($area_id)) {

            if ($area_id != '') {
                $deliverlocation = $area_id;
                if (is_numeric($deliverlocation)) {
                    $location = $deliverlocation;
                } else {
                    $end = strlen($deliverlocation);
                    $from = strpos($deliverlocation, '_') + 1;
                    $address_id = substr($deliverlocation, $from, $end);
                    $location = \common\models\CustomerAddress::findOne($address_id)->area_id;
                }
            }

            $delivery_area = CustomerCart::checkLocation($location, $vendor_id);

            if (!$delivery_area)
            {
                $json['error'] = Yii::t('frontend', 'Delivery not available on selected area');

                return $json;
            }
        }

        //get item type

        $item_type = ItemType::findOne($item->type_id);

        if($item_type) {
            $item_type_name = $item_type->type_name;
        } else {
            $item_type_name = 'Product';
        }

        $i = -1; //-1 to start with selected date

        while(true)
        {
            $i++;

            //check upto 7 days

            if($i == 7)
                break;

            $timestamp = strtotime($delivery_date) + ($i * 24 * 60 * 60);

            $delivery_date = date('Y-m-d', $timestamp);

            //check timeslot available on selected date

            $timeslot = VendorWorkingTiming::find()
                ->defaultTiming()
                ->vendor($item->vendor_id)
                ->workingDay(date('l', strtotime($delivery_date)))
                ->one();

            if(!$timeslot)
            {
                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Delivery timeslot not available');

                continue;
            }

            if($item->notice_period_type == 'Hour' && !empty($time_slot))
            {
                $min_delivery_time = strtotime('+'.$item->item_how_long_to_make.' hours');
                $delivery_time = strtotime($delivery_date.' '.$time_slot);

                if($delivery_time < $min_delivery_time)
                {
                    if($i == 0)
                        $json['error'] = Yii::t('frontend', 'Item notice period {count} hour(s)!', [
                            'count' => $item->item_how_long_to_make
                        ]);

                    continue;
                }
            }

            if($item->notice_period_type == 'Day' && !empty($delivery_date))
            {
                //compare timestamp of date

                $min_delivery_time = strtotime(date('Y-m-d', strtotime('+'.$item->item_how_long_to_make.' days')));
                $delivery_time = strtotime($delivery_date);

                if($delivery_time < $min_delivery_time)
                {
                    if($i == 0)
                        $json['error'] = Yii::t('frontend', 'Item notice period {count} day(s)!', [
                            'count' => $item->item_how_long_to_make
                        ]);

                    continue;
                }
            }

            //-------------- Start Item Capacity -----------------//
            //default capacity is how many of it they can process per day

            //1) get capacity exception for selected date

            $capacity_exception = \common\models\VendorItemCapacityException::find()
                ->item($item_id)
                ->exceptionDate($delivery_date)
                ->one();

            if ($capacity_exception && $capacity_exception->exception_capacity) {
                $capacity = $capacity_exception->exception_capacity;
            } else {
                $capacity = $item->item_default_capacity;
            }

            $query = CustomerCart::find()
                ->item($item_id)
                ->deliveryDate(date('Y-m-d', strtotime($delivery_date)))
                ->valid()
                ->defaultCart();

            $query->user();

            $in_cart = $query->sum('cart_quantity');

            //2) get no of item purchased for selected date
            $purchased_result = \common\models\Booking::totalPurchasedItem($item_id, $delivery_date);

            if ($purchased_result) {
                $purchased = $purchased_result['purchased'];
            } else {
                $purchased = 0;
            }

            if (($purchased+$in_cart) >= $capacity)
            {
                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

                continue;
            }

            //-------------- END Item Capacity -----------------//

            //current date should not in blocked date
            $block_date = \common\models\BlockedDate::find()
                ->vendor($vendor_id)
                ->blockedDate($delivery_date)
                ->one();

            if ($block_date)
            {
                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

                continue;
            }

            //day should not in week off
            $blocked_days = explode(',', Vendor::findOne($vendor_id)->blocked_days);
            $day = date('N', strtotime($delivery_date));//7-sunday, 1-monday

            if (in_array($day, $blocked_days))
            {
                //return error only for selected date

                if($i == 0)
                    $json['error'] = Yii::t('frontend', 'Item is not available on selected date');

                continue;
            }

            // we are lucky! Item available for selected date

            if($i == 0)
            {
                $json['date'] = $delivery_date;
                $json['capacity'] = $capacity;
                $json['price'] = VendorItem::itemFinalPrice($item_id, $quantity, (isset($menu_item)) ? $menu_item : []);
            }
            else //available for other date
            {
                $json['error'] = 'Item available on '.date('d-m-Y', strtotime($delivery_date));
            }

            break;
        }

        return $json;
    }

    /**
     * @inheritdoc
     * @return query\CustomerCartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\CustomerCartQuery(get_called_class());
    }
}
