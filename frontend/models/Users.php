<?php

namespace frontend\models;

use yii\base\Model;
use Yii\db\Query;
use Yii;
use frontend\models\Users;
use common\models\Events;
use frontend\models\Themes;
use common\models\CustomerAddress;

/**
 * Signup form.
 */
class Users extends Model
{
    //Success
    const KEY_MATCH = 2;
    const SUCCESS = 1;
    // Failure
    const FAILURE = 0;
    const KEY_NOT_MATCH = 1;
    const EMAIL_NOT_EXIST = -1;
    

    public $content;
    public static function tableName()
    {
        return '{{%customer}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'confirm_password', 'username', 'bday', 'bmonth', 'byear', 'gender', 'phone', 'customer_dateofbirth'], 'required'],
            [['country', 'area', 'created_by', 'created_datetime'], 'integer'],
            [['block', 'street', 'juda', 'phone', 'extra'], 'string'],
            [['email'], 'unique'],
            [['phone'], 'match', 'pattern' => '/^[0-9+ -]+$/', 'message' => 'Phone number accept only numbers and +,-'],
            [['email'], 'email'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new self();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return;
    }

    public function get_customer_details($customer_id)
    {
     return $ads = (new Query())
            ->select('item_id')
            ->from('{{%wishlist}}')
            ->where(['customer_id'=>$customer_id])
            ->all();
    }

    public static function get_user_details()
    {
        return $ads = Customer::find()
        ->select('{{%customer_address}}.*,{{%customer}}.*')
        ->leftjoin('{{%customer_address}}','{{%customer_address}}.customer_id = {{%customer}}.customer_id')
        ->where(['{{%customer}}.customer_id'=>Yii::$app->user->identity->customer_id])
        ->asArray()
        ->all();
    }

    public function check_authorization($email)
    {
      $user = Customer::find()->select('customer_id,customer_activation_status,customer_status,
        trash,customer_name,customer_email')->where(['trash'=>"Default",'customer_email'=>$email])
      ->asArray()
      ->all();
        if (count($user) > 0) {
            if ($user[0]['customer_activation_status'] == 0) {
                return -1;
            } elseif ($user[0]['customer_status'] == 'Deactive') {
                return -2;
            } elseif ($user[0]['trash'] == 'deleted') {
                return -3;
            } else {
                return $user;
            }
        } else {
            return -3;
        }
    }

    public function customer_password_reset($password, $customer_activation_key,$user_email)
    {
        $gen_password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $new_activation_key = self::generateRandomString();
		$command = Customer::updateAll(['customer_password' => $gen_password],['customer_activation_key'=>$customer_activation_key]);
        return $update_key = Customer::updateAll(['customer_activation_key' => $new_activation_key],['customer_email'=>$user_email['customer_email']]);
    }

    public function update_customer_profile($post, $customer_id)
    {
        $customer_dateofbirth = $post['byear'].'-'.$post['bmonth'].'-'.$post['bday'];
        $customer = Customer::updateAll(['customer_name' => $post['first_name'],'customer_last_name' => $post['last_name'],'customer_gender' => $post['gender'],'customer_dateofbirth' => $customer_dateofbirth,'customer_mobile' => $post['mobile_number']],['customer_id'=>$customer_id]);
        $address_count = CustomerAddress::find()->where(['customer_id'=>$customer_id])->count();
        if($address_count == 0)
        {
           $command = new CustomerAddress();
           $command->country_id = $post['country'];
           $command->city_id = $post['city'];
           //$command->address_data = $post['address_name'];
           $command->city_id = $post['city'];
           $command->customer_id = $customer_id;
           return $command->save();
        }
        else
        {
            return $command = CustomerAddress::updateAll(['country_id' => $post['country'],'city_id' => $post['city']],['customer_id'=>$customer_id]);
        }

    }


    public function update_event($event_name, $event_type, $event_date, $event_id)
    {
        $customer_id = Yii::$app->user->identity->customer_id;
        $event_date = date('Y-m-d', strtotime($event_date));
        $string = str_replace(' ', '-', $event_name); // Replaces all spaces with hyphens.
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $check = Events::find()->select('event_id')->where(['event_id'=>$event_id,'event_name'=>$event_name,
            'customer_id'=>$customer_id])->asArray()->all();
        if (count($check) > 0) {
            return -1;
        } else {
			return $command=Events::updateAll(['customer_id' => $customer_id,'event_name' => $event_name,'event_date' => $event_date,'event_type' => $event_type,'slug' => $slug],'event_id= '.$event_id);
        }
    }


    public function update_wishlist($item_id, $customer_id)
    {

        $user_fav = Wishlist::find()->select(['wish_status'])
				->where(['customer_id'=>$customer_id])
				->andWhere(['item_id'=>$item_id])
				->count();
        if ($user_fav > 0) {
			$command = Wishlist::deleteAll(['item_id'=>$item_id,'customer_id'=>$customer_id]);
            return -1;
        } else {
			$wish_modal= new Wishlist;
			$wish_modal->item_id=$item_id;
			$wish_modal->customer_id=$customer_id;
			$wish_modal->wish_status=1;
			$wish_modal->save();
            return 1;
        }
    }

    public function update_wishlist_succcess($item_id, $customer_id)
    {
		$user_fav = Wishlist::find()->select(['wish_status'])
				->where(['customer_id'=>$customer_id])
				->andwhere(['item_id'=>$item_id])
				->count();
        if ($user_fav > 0) {
			$command=Events::updateAll(['wish_status' => 1],['customer_id= '.$customer_id,'item_id= '.$item_id]);
            return 1;
        } else {

			$wish_modal= new Wishlist;
			$wish_modal->item_id=$item_id;
			$wish_modal->customer_id=$customer_id;
			$wish_modal->wish_status=1;
			$wish_modal->save();
            return 1;
        }
    }

    public function delete_wishlist($item_id, $customer_id)
    {
		$command = Wishlist::deleteAll(['item_id'=>$item_id,'customer_id'=>$customer_id]);
        return 1;
    }

    public function get_customer_events_count($customer_id, $type)
    {
		$condn='';
		if ($type != '') {
			$condn.= '["{{events}}.event_type"=> $type]';
        }
        return $events = Events::find()
        ->select(['{{%event_item_link}}.event_id as item_count','{{%events}}.event_id','{{%events}}.event_name','{{%events}}.slug','{{%events}}.event_type','{{%events}}.event_date'])
        ->leftJoin('{{%event_item_link}}', '{{%event_item_link}}.event_id = {{%events}}.event_id')
        ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%event_item_link}}.item_id')
        ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
        ->Where(['{{%events}}.customer_id'=>$customer_id]);
            if(!empty($type)) $events->andWhere([$condn]);
            $events->groupby(['{{%events}}.event_id'])
            ->orderby(['{{%events}}.event_date' => SORT_ASC])
            ->limit($offset,$limit)
            ->asArray()
    		->count();
    }

    public function getCustomerEvents($customer_id, $limit, $offset, $type)
    {
		$condn='';
        if ($type != '') {
			$condn.= "{{events}}.event_type=".$type;
        }
        else
        {
            $condn.= "{{events}}.type_id=";
        }

        $events = Events::find()
        ->select(['{{%event_item_link}}.event_id as item_count','{{%events}}.event_id','{{%events}}.event_name','{{%events}}.slug','{{%events}}.event_type','{{%events}}.event_date'])
        ->leftJoin('{{%event_item_link}}', '{{%event_item_link}}.event_id = {{%events}}.event_id')
        ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%event_item_link}}.item_id')
        ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
        ->with('{{%events}}');

        if(!empty($type)){
            $events->where([$condn]);
        }

        $events->groupby(['{{%events}}.event_id'])
        ->orderby(['{{%events}}.event_date' => SORT_ASC])
        ->limit($offset,$limit)
        ->asArray()
        ->all();

        return $events;
    }

    public static function get_customer_wishlist_details($customer_id)
    {
		return $result = Wishlist::find()->select(['item_id'])
						->where(['customer_id' => $customer_id])
						->asArray()
						->all();
    }

    public function get_customer_wishlist_count($customer_id, $category, $price, $vendor, $avail_sale, $theme)
    {
		 //print_r ($category);die;
        $condn = '';
        $order_by = '';
        $join = '';
        if ($category != '') {
			$condn.= '["{{category}}.category_id"=> $category]';
        }
        $vendr='';
        if ($vendor != '') {
			$vendr .= '["{{vendor}}.vendor_id"=> $vendor]';
        }
        $availsafe='';
        if ($avail_sale != '') {
            if ($avail_sale == 0) {
				$availsafe .= '["{{vendor_item}}.item_for_sale"=> "no"]';
            } else {
				$availsafe .= '["{{vendor_item}}.item_for_sale"=> "yes"]';
            }
        }

        if ($price != '') {
            if ($price == 0) {
				//'usertype'=>SORT_ASC,

				$order_by .= '["{{vendor_item}}.item_price_per_unit"=> SORT_ASC]';
            } elseif ($price == 1) {
				$order_by .= '["{{vendor_item}}.item_price_per_unit"=> SORT_DESC]';
            }
        } else {
			$order_by .= '["{{vendor_item}}.item_name"=> ASC_DESC]';
        }
        $today = date('Y-m-d H:i:s');
        return $wishlist=Wishlist::find()->select(['{{%wishlist}}.customer_id','{{%wishlist}}.item_id','{{%vendor_item}}.item_name','{{%vendor_item}}.item_description','{{%vendor_item}}.item_price_per_unit','{{%vendor}}.vendor_name'])
        ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%wishlist}}.item_id')
        ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
        ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
        ->where(['{{%wishlist}}.customer_id'=> $customer_id])
        ->andwhere(['{{%vendor}}.trash'=> 'Default'])
        ->andWhere(['{{%vendor}}.approve_status'=> 'Yes'])
        ->andWhere(['<=','{{%vendor}}.package_start_date',$today])
        ->andWhere(['>=','{{%vendor}}.package_end_date',$today])
        ->andWhere(['>','{{%vendor_item}}.item_amount_in_stock',0])
        ->andWhere(['{{%vendor_item}}.item_approved'=>'yes'])
        ->andWhere(['{{%vendor_item}}.item_archived'=>'no'])
        ->andWhere(['{{%category}}.category_allow_sale'=>'yes'])
        ->andWhere(['{{%category}}.trash'=>'Default'])
        ->andWhere(['{{%vendor_item}}.trash'=>'Default'])
        ->andWhere(['{{%vendor_item}}.item_status'=>'Active'])
        ->andWhere($condn)
        ->andWhere($vendr)
        ->andWhere($availsafe)
        ->asArray()
        ->count();
    }

    public function get_customer_wishlist($customer_id, $limit, $offset, $category, $price, $vendor, $avail_sale, $theme)
    {
        //print_r ($category);die;
        $condn = '';
        $order_by = '';
        $join = '';
        if ($category != '') {
			$condn.= '["{{category}}.category_id"=> $category]';
        }
        $vendr='';
        if ($vendor != '') {
			$vendr .= '["{{vendor}}.vendor_id"=> $vendor]';
        }
        $availsafe='';
        if ($avail_sale != '') {
            if ($avail_sale == 0) {
				$availsafe .= '["{{vendor_item}}.item_for_sale"=> "no"]';
            } else {
				$availsafe .= '["{{vendor_item}}.item_for_sale"=> "yes"]';
            }
        }

        if ($price != '') {
            if ($price == 0) {
				//'usertype'=>SORT_ASC,

				$order_by .= '["{{vendor_item}}.item_price_per_unit"=> SORT_ASC]';
            } elseif ($price == 1) {
				$order_by .= '["{{vendor_item}}.item_price_per_unit"=> SORT_DESC]';
            }
        } else {
			$order_by .= '["{{vendor_item}}.item_name"=> SORT_ASC]';
        }
        $today = date('Y-m-d H:i:s');
        return $wishlist=Wishlist::find()->select(['{{%wishlist}}.customer_id','{{%wishlist}}.item_id','{{%vendor_item}}.item_name','{{%vendor_item}}.item_description','{{%vendor_item}}.item_price_per_unit','{{%vendor}}.vendor_name'])
        ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%wishlist}}.item_id')
        ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
        ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
        ->where(['{{%wishlist}}.customer_id'=> $customer_id])
        ->andwhere(['{{%vendor}}.trash'=> 'Default'])
        ->andWhere(['{{%vendor}}.approve_status'=> 'Yes'])
        ->andWhere(['<=','{{%vendor}}.package_start_date',$today])
        ->andWhere(['>=','{{%vendor}}.package_end_date',$today])
        ->andWhere(['>','{{%vendor_item}}.item_amount_in_stock',0])
        ->andWhere(['{{%vendor_item}}.item_approved'=>'yes'])
        ->andWhere(['{{%vendor_item}}.item_archived'=>'no'])
        ->andWhere(['{{%category}}.category_allow_sale'=>'yes'])
        ->andWhere(['{{%category}}.trash'=>'Default'])
        ->andWhere(['{{%vendor_item}}.trash'=>'Default'])
        ->andWhere(['{{%vendor_item}}.item_status'=>'Active'])
        ->andWhere($condn)
        ->andWhere($vendr)
        ->andWhere($availsafe)
        //->orderby($order_by)
        ->asArray()
        ->all();
    }

    /* BEGIN Get customer wish list */
    public static function loadCustomerWishlist($customer_id)
    {
		$today = date('Y-m-d H:i:s');
        $data=Wishlist::find()
        ->select(['{{%vendor}}.vendor_name',
                    '{{%vendor_item}}.slug',
                    '{{%vendor_item}}.item_id',
                    '{{%vendor_item}}.item_name',
                    '{{%vendor_item}}.item_price_per_unit'])
            ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%wishlist}}.item_id')
            ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
            ->where(['{{%wishlist}}.customer_id'=>$customer_id])
			->andwhere(['{{%vendor_item}}.item_for_sale'=>'Yes'])
			->andWhere(['{{%vendor_item}}.item_status'=>'Active'])
			->andWhere(['{{%vendor_item}}.trash'=>'Default'])
			->andWhere(['{{%vendor_item}}.type_id'=>'2'])
			->andWhere(['{{%wishlist}}.wish_status'=>'1'])
			->asArray()
			->all();
        return $data;
    }
    /* END Get customer wish list */

    public static function vendor_list()
    {
        $today = date('Y-m-d H:i:s');
        return $vendor = Vendor::find()->select('vendor_id,vendor_name')
                        ->where(['vendor_Status'=>'Active','trash'=>"Default",'approve_status'=>"Yes",
                            'package_start_date'=>"Yes"])
                        ->andwhere(['<=','package_end_date',$today])
                        ->andWhere(['>=','package_end_date',$today])
                        ->orderBy('vendor_name ASC')
                        ->asArray()
                        ->all();
    }

    public static function get_main_category()
    {
        return $general = Category::find()->select('category_id,category_name')
                        ->where(['parent_category_id'=>'IS NULL'])
                        ->andwhere(['trash'=>'Default'])
                        ->andwhere(['category_allow_sale'=>'yes'])
                        ->asArray()
                        ->all();
    }

    public static function get_themes()
    {
        return $general = Themes::find()->select('theme_id,theme_name')
                        ->where(['trash'=>'Default'])
                        ->andWhere(['theme_status'=>'Active'])
                        ->asArray()
                        ->all();
    }

    public static function check_email_exist($custemail)
    {
          return $exist = Customer::find()->select('customer_email')
                        ->where(['customer_email'=>$custemail])
                        ->asArray()
                        ->one();

    }
    public static function check_user_exist($custemail)
    {
         return $exist = Customer::find()->select('customer_activation_key')
                        ->where(['customer_email'=>$custemail])
                        ->asArray()
                        ->all();
    }

    public function update_datetime_user($key)
    {
        $time = date('Y-m-d H:i:s');
        return $command=Customer::updateAll(['modified_datetime' => $time],['customer_activation_key'=>$key]);
    }
    public static function check_customer_validtime($key)
    {
        $time = date('Y-m-d H:i:s');
        return $validtime = Customer::find()->select(['customer_id'])
                        ->where(['customer_activation_key'=>$key])
                        ->andWhere('TIMESTAMPDIFF(MINUTE,modified_datetime,'.$time.')' <= 1440)
                        ->asArray()
                        ->all();
    }

    public function insert_item_to_event($item_id, $event_id)
    {
        $customer_id = Yii::$app->user->identity->customer_id;
        $check = Eventitemlink::find()->select('link_id')
                        ->where(['event_id'=>$event_id])
                        ->andWhere(['item_id'=>$item_id])
                        ->count();
        if ($check > 0) {
            return self::EVENT_ALREADY_EXIST;
        } else {
            $event_date = date('Y-m-d H:i:s');
            $command = new Eventitemlink();
            $command->event_id = $event_id;
            $command->item_id = $item_id;
            $command->link_datetime = $event_date;
            $command->created_datetime = $event_date;
            $command->modified_datetime = $event_date;
            if($command->save())
            {
                return self::EVENT_ADDED_SUCCESS;
            }
        }
    }

    public function check_valid_key($key)
    {
     $check_key = Customer::find()
      ->select(['customer_id'])
      ->where(['customer_activation_status'=>0])
      ->andWhere(['customer_activation_key'=>$key])
      ->asArray()
      ->all();
        if (count($check_key) > 0) {
          $command=Customer::updateAll(['customer_activation_status' => 1],['customer_activation_key'=>$key]);
            if ($command) {
                return self::KEY_MATCH;
            }
        } else {
            return self::KEY_NOT_MATCH;
        }
    }

    public function customer_logindetail($key)
    {
      return$check_key = Customer::find()
      ->select(['customer_email'])
      ->where(['customer_activation_key'=>$key])
      ->asArray()
      ->all();
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}
