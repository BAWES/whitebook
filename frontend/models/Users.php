<?php

namespace frontend\models;

use common\models\User;
use common\models\Customer;
use common\models\Events;
use common\models\Themes;
use yii\base\Model;
use Yii\db\Query;
use Yii;

/**
 * Signup form.
 */
class Users extends Model
{
    public $content;
    public static function tableName()
    {
        return 'whitebook_customer';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'confirm_password', 'username', 'bday', 'bmonth', 'byear', 'gender', 'phone', 'customer_dateofbirth'], 'required'],
            [['country', 'area', 'created_by', 'created_datetime'], 'integer'],
            [['customer_address', 'block', 'street', 'juda', 'phone', 'extra'], 'string'],
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
            ->from('{{%_wishlist}}')
            ->where(['customer_id'=>$customer_id])
            ->all();
    }

    public static function get_user_details($customer_id)
    {
        return $ads = Customer::find()->select('*')->where(['customer_id'=>$customer_id])->asArray()->all();
    }

    public function check_authorization($email, $password)
    {
      $user = Customer::find()->select('customer_id,customer_activation_status,customer_status,trash,customer_name,customer_email')->where(['trash'=>"Default",'customer_email'=>$email,
            'customer_org_password'=>$password])->asArray()->all();
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

    public function customer_password_reset($password, $customer_activation_key)
    {
        $gen_password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $sql = 'UPDATE whitebook_customer set customer_org_password="'.$password.'",customer_password="'.$gen_password.'" where customer_activation_key="'.$customer_activation_key.'"';
        $command = Yii::$app->DB->createCommand($sql);
        $customer = $command->execute();
        return $customer;
    }

    public function update_customer_profile($post, $customer_id)
    {
        $password = Yii::$app->getSecurity()->generatePasswordHash($post['customer_password']);
        $customer_dateofbirth = $post['byear'].'-'.$post['bmonth'].'-'.$post['bday'];
        $sql = 'UPDATE whitebook_customer set customer_name = "'.$post['first_name'].'",customer_last_name="'.$post['last_name'].'",customer_org_password="'.$post['customer_password'].'",customer_password="'.$password.'",customer_gender="'.$post['gender'].'",customer_dateofbirth="'.$customer_dateofbirth.'",customer_mobile="'.$post['mobile_number'].'" ,customer_address="'.$post['address_name'].'" ,country="'.$post['country'].'" ,area="'.$post['city'].'" ,block="'.$post['block'].'" ,street="'.$post['street'].'" ,juda="'.$post['juda'].'" ,phone="'.$post['phone'].'",extra="'.$post['extra'].'" where customer_id="'.$customer_id.'"';
        $command = Yii::$app->DB->createCommand($sql);
        $customer = $command->execute();

        return $customer;
    }

    public function create_event($event_name, $event_type, $event_date)
    {
        $customer_id = Yii::$app->params['CUSTOMER_ID'];
        $event_date = date('Y-m-d', strtotime($event_date));
        $string = str_replace(' ', '-', $event_name); // Replaces all spaces with hyphens.
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $check = Events::find()->select('event_id')->where(['customer_id'=>$customer_id,'event_name'=>$event_name])->asArray()->all();
        if (count($check) > 0) {
            return -1;
        } else {
            $command = Yii::$app->DB->createCommand(
        'INSERT into whitebook_events(customer_id,event_name,event_date,event_type,slug) values("'.$customer_id.'","'.$event_name.'","'.$event_date.'","'.$event_type.'","'.$slug.'")');
            $event = $command->execute();

            return Yii::$app->DB->lastInsertID;
        }
    }

    public function update_event($event_name, $event_type, $event_date, $event_id)
    {
        $customer_id = Yii::$app->params['CUSTOMER_ID'];
        $event_date = date('Y-m-d', strtotime($event_date));
        $string = str_replace(' ', '-', $event_name); // Replaces all spaces with hyphens.
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $check = Events::find()->select('event_id')->where(['event_id'=>$event_id,'event_name'=>$event_name,
            'customer_id'=>$customer_id])->asArray()->all();
        if (count($check) > 0) {
            return -1;
        } else {
            $command = Yii::$app->DB->createCommand(
            'UPDATE whitebook_events SET customer_id="'.$customer_id.'", event_name="'.$event_name.'",event_date="'.$event_date.'",event_type="'.$event_type.'",slug="'.$slug.'" WHERE event_id ='.$event_id);
            $event = $command->execute();

            return $slug;
        }
    }

    public function insert_item_to_event($item_id, $event_id)
    {
        $customer_id = Yii::$app->params['CUSTOMER_ID'];
        $check = (new Query())
                ->select('link_id')
                ->from('whitebook_event_item_link')
                ->where('event_id' => $event_id)
                ->andwhere('item_id' => $item_id)
                ->all();
        if (count($check) > 0) {
            return -2;
        } else {
            $event_date = date('Y-m-d H:i:s');
            $command = Yii::$app->DB->createCommand(
            'INSERT into whitebook_event_item_link(event_id,item_id,link_datetime,created_datetime,modified_datetime) values("'.$event_id.'","'.$item_id.'","'.$event_date.'","'.$event_date.'","'.$event_date.'")');
            $event = $command->execute();

            return 1;
        }
    }

    public function update_wishlist($item_id, $customer_id)
    {
     $user_fav = (new Query())
                ->select('wish_status')
                ->from('whitebook_wishlist')
                ->where('customer_id' => $customer_id)
                ->andwhere('item_id' => $item_id)
                ->all();
        if (count($user_fav) > 0) {
            $command = Yii::$app->DB->createCommand(
            'DELETE from whitebook_wishlist where item_id='.$item_id.' and customer_id='.$customer_id);
            $delete = $command->execute();

            return -1;
        } else {
            $command = Yii::$app->DB->createCommand(
            'INSERT into whitebook_wishlist(item_id,customer_id,wish_status) values("'.$item_id.'","'.$customer_id.'","1")');
            $event = $command->execute();

            return 1;
        }
    }

    public function update_wishlist_succcess($item_id, $customer_id)
    {
        $user_fav = (new Query())
                ->select('wish_status')
                ->from('whitebook_wishlist')
                ->where('customer_id' => $customer_id)
                ->andwhere('item_id' => $item_id)
                ->all();
        if (count($user_fav) > 0) {
            $sql = 'UPDATE whitebook_wishlist set wish_status="1" where customer_id="'.$customer_id.'" and  item_id="'.$item_id.'"';
            $command = Yii::$app->DB->createCommand($sql);
            $customer = $command->execute();
            return 1;
        } else {
            $command = Yii::$app->DB->createCommand(
            'INSERT into whitebook_wishlist(item_id,customer_id,wish_status) values("'.$item_id.'","'.$customer_id.'","1")');
            $event = $command->execute();

            return 1;
        }
    }

    public function delete_wishlist($item_id, $customer_id)
    {
        $command = Yii::$app->DB->createCommand(
            'DELETE from whitebook_wishlist where item_id='.$item_id.' and customer_id='.$customer_id);
        $delete = $command->execute();

        return 1;
    }

    public function get_customer_events_count($customer_id, $type)
    {
        $condn = '';
        if ($type != '') {
            $condn .= " AND we.event_type='$type'";
            $condn .= " AND wvi.item_status='Active' AND wvi.item_for_sale='Yes' AND wvi.trash='default' AND item_type=2";
        }
        $command = Yii::$app->DB->createCommand(
        'SELECT we.event_id FROM {{%events}} as we
		LEFT JOIN {{%event_item_link}} as weil ON weil.event_id= we.event_id
		LEFT JOIN {{%vendor_item}} as wvi ON wvi.item_id = weil.item_id
		WHERE we.customer_id="'.$customer_id.'" '.$condn.' GROUP BY we.event_id order by we.event_date asc');
        $events = $command->queryAll();

        return count($events);
    }

    public function getCustomerEvents($customer_id, $limit, $offset, $type)
    {
        $condn = '';
        if ($type != '') {
            $condn .= " AND whitebook_events.event_type='$type'";
        }
        $command = Yii::$app->DB->createCommand(
        'SELECT count(whitebook_event_item_link.event_id)  as item_count, whitebook_events.event_id,whitebook_events.event_name,whitebook_events.slug,whitebook_events.event_type,whitebook_events.event_date FROM whitebook_events
		LEFT JOIN whitebook_event_item_link on whitebook_event_item_link.event_id= whitebook_events.event_id WHERE whitebook_events.customer_id="'.$customer_id.'"  '.$condn.'  GROUP BY whitebook_events.event_id order by event_date asc limit '.$offset.','.$limit);
        $events = $command->queryAll();

        return $events;
    }

    public static function get_customer_wishlist_details($customer_id)
    {
        return $command1 = (new Query())
                ->select('item_id')
                ->from('whitebook_wishlist')
                ->where('customer_id' => $customer_id)
                ->all();
                 die;
    }

    public function get_customer_wishlist_count($customer_id, $category, $price, $vendor, $avail_sale, $theme)
    {
        $condn = '';
        $order_by = '';
        if ($category != '') {
            $condn .= " AND whitebook_category.category_id=$category";
        }
        if ($vendor != '') {
            $condn .= " AND whitebook_vendor.vendor_id=$vendor";
        }
        if ($avail_sale != '') {
            if ($avail_sale == 0) {
                $condn .= " AND whitebook_vendor_item.item_for_sale='no'";
            } else {
                $condn .= " AND whitebook_vendor_item.item_for_sale='yes'";
            }
        }
        if ($price != '') {
            if ($price == 0) {
                $order_by .= ' order by whitebook_vendor_item.item_price_per_unit asc';
            } elseif ($price == 1) {
                $order_by .= ' order by whitebook_vendor_item.item_price_per_unit desc';
            }
        } else {
            $order_by .= ' order by whitebook_vendor_item.item_name asc';
        }
        $today = date('Y-m-d H:i:s');
        $command = Yii::$app->DB->createCommand(
        'SELECT whitebook_wishlist.item_id FROM whitebook_wishlist
		JOIN whitebook_vendor_item on whitebook_vendor_item.item_id= whitebook_wishlist.item_id
		JOIN whitebook_vendor on whitebook_vendor_item.vendor_id= whitebook_vendor.vendor_id
		JOIN whitebook_category on whitebook_category.category_id=whitebook_vendor_item.category_id
		WHERE whitebook_wishlist.customer_id="'.$customer_id.'"
		AND whitebook_vendor.vendor_Status="Active"
		AND whitebook_vendor.trash="Default"
		AND whitebook_vendor.approve_status="Yes"
		AND whitebook_vendor.package_start_date<="'.$today.'"
		AND whitebook_vendor.package_end_date>="'.$today.'"
		AND whitebook_vendor_item.item_amount_in_stock>0
		AND whitebook_vendor_item.item_approved="yes"
		AND whitebook_vendor_item.item_archived="no"
		AND whitebook_category.category_allow_sale="yes"
		AND whitebook_category.trash="Default"
		AND whitebook_vendor_item.trash="Default"
		AND whitebook_vendor_item.item_status="Active" '.$condn.' '.$order_by.'');
        $events = $command->queryAll();

        return count($events);
    }

    public function get_customer_wishlist($customer_id, $limit, $offset, $category, $price, $vendor, $avail_sale, $theme)
    {
        //print_r ($category);die;
        $condn = '';
        $order_by = '';
        $join = '';
        if ($category != '') {
            $condn .= " AND whitebook_category.category_id=$category";
        }
        if ($vendor != '') {
            $condn .= " AND whitebook_vendor.vendor_id=$vendor";
        }
        if ($avail_sale != '') {
            if ($avail_sale == 0) {
                $condn .= " AND whitebook_vendor_item.item_for_sale='no'";
            } else {
                $condn .= " AND whitebook_vendor_item.item_for_sale='yes'";
            }
        }

        if ($price != '') {
            if ($price == 0) {
                $order_by .= ' order by whitebook_vendor_item.item_price_per_unit asc';
            } elseif ($price == 1) {
                $order_by .= ' order by whitebook_vendor_item.item_price_per_unit desc';
            }
        } else {
            $order_by .= ' order by whitebook_vendor_item.item_name asc';
        }
        $today = date('Y-m-d H:i:s');
        $command = Yii::$app->DB->createCommand(
        'SELECT whitebook_wishlist.item_id,whitebook_vendor_item.item_name,whitebook_vendor_item.item_description,whitebook_vendor.vendor_name,whitebook_vendor_item.item_price_per_unit FROM whitebook_wishlist
		JOIN whitebook_vendor_item on whitebook_vendor_item.item_id= whitebook_wishlist.item_id
		JOIN whitebook_vendor on whitebook_vendor_item.vendor_id= whitebook_vendor.vendor_id
		JOIN whitebook_category on whitebook_category.category_id=whitebook_vendor_item.category_id
		WHERE whitebook_wishlist.customer_id="'.$customer_id.'"
		AND whitebook_vendor.vendor_Status="Active"
		AND whitebook_vendor.trash="Default"
		AND whitebook_vendor.approve_status="Yes"
		AND whitebook_vendor.package_start_date<="'.$today.'"
		AND whitebook_vendor.package_end_date>="'.$today.'"
		AND whitebook_vendor_item.item_amount_in_stock>0
		AND whitebook_vendor_item.item_approved="yes"
		AND whitebook_vendor_item.item_archived="no"
		AND whitebook_category.category_allow_sale="yes"
		AND whitebook_category.trash="Default"
		AND whitebook_vendor_item.trash="Default"
		AND whitebook_vendor_item.item_status="Active" '.$condn.' '.$order_by.' limit '.$offset.','.$limit);
        $events = $command->queryAll();

        return $events;
    }

    /* BEGIN Get customer wish list */
    public static function loadCustomerWishlist($customer_id)
    {
        $query = new Query;
        $query  ->select([
                'wvi.item_id','wvi.slug', 'wvi.item_name', 'wvi.item_price_per_unit','wv.vendor_name',
                'whitebook_events.event_id'])
            ->from('whitebook_wishlist AS ww')
            ->join('LEFT JOIN', 'whitebook_vendor_item AS wvi',
                        'wvi.item_id =ww.item_id')
            ->join('LEFT JOIN', 'whitebook_vendor AS wv',
                        'wv.vendor_id=wvi.vendor_id')
            ->where('ww.customer_id="default"')
            ->andwhere('wvi.item_status="Active"')
            ->andwhere('wvi.item_for_sale="Yes"')
            ->andwhere('wvi.trash="default"')
            ->andwhere('type_id=2')
            ->andwhere('wvi.wish_status=1');
        $command = $query->createCommand();
        return $event_type1 = $command->queryAll();
    }
    /* END Get customer wish list */

    public static function vendor_list()
    {
        $today = date('Y-m-d H:i:s');
        return $vendor = Vendor::find()->select('vendor_id,vendor_name')
                        ->where(['vendor_Status'=>'Active','trash'=>"Default",'approve_status'=>"Yes",
                            'package_start_date'=>"Yes"])
                        ->andwhere(['<=','package_end_date',$today])
                        ->andwhere(['>=','package_end_date',$today])
                        ->orderBy('vendor_name ASC')
                        ->asArray()
                        ->all();
    }

    public static function get_main_category()
    {
        return $general = Category::find()->select('category_id,category_name,category_url')
                        ->where(['parent_category_id'=>'IS NULL'])
                        ->andwhere(['trash'=>'Default'])
                        ->andwhere(['category_allow_sale'=>'yes'])
                        ->asArray()
                        ->all();
    }

    public static function get_themes()
    {
        return $general = Theme::find()->select('theme_id,theme_name')
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
                        ->all();

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
        return Yii::$app->db->createCommand()
            ->update('whitebook_customer', ['modified_datetime' => $time], 'customer_activation_key ='.$key)
            ->execute();
    }
    public static function check_customer_validtime($key)
    {
        $time = date('Y-m-d H:i:s');
        return $validtime = Customer::find()->select('customer_activation_key')
                        ->where(['customer_email'=>$custemail])
                        ->andWhere(['<=','TIMESTAMPDIFF(MINUTE,modified_datetime,"$time")','1440'])
                        ->asArray()
                        ->all();
    }
}
