<?php

namespace frontend\models;

use common\models\User;
use yii\base\Model;
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
        $command = Yii::$app->DB->createCommand(
        'SELECT item_id FROM whitebook_wishlist where customer_id='.$customer_id.'');
        $ads = $command->queryAll();

        return $ads;
        die;
        $k = array();
        if (!empty($ads)) {
            foreach ($ads as $a) {
                $k[] = $e['item_id'];
            }
        }

        return $k;
    }

    public static function get_user_details($customer_id)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_customer where customer_id='.$customer_id.'');
        $ads = $command->queryAll();

        return $ads;
    }

    public function check_authorization($email, $password)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT customer_id,customer_activation_status,customer_status,trash,customer_name,customer_email FROM whitebook_customer where  trash="Default" and customer_email="'.$email.'" and customer_org_password="'.$password.'"');

        $user = $command->queryAll();
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
        $command = Yii::$app->DB->createCommand(
        'SELECT event_id FROM whitebook_events WHERE event_name="'.$event_name.'" and customer_id="'.$customer_id.'"');
        $check = $command->queryAll();
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
        $command = Yii::$app->DB->createCommand(
        'SELECT event_id FROM whitebook_events WHERE event_id!="'.$event_id.'" and event_name="'.$event_name.'" and event_id="'.$event_id.'" and customer_id="'.$customer_id.'"');
        $check = $command->queryAll();
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
        $command = Yii::$app->DB->createCommand(
        'SELECT link_id FROM whitebook_event_item_link WHERE event_id="'.$event_id.'" and item_id="'.$item_id.'"');
        $check = $command->queryAll();
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
        $command = Yii::$app->DB->createCommand(
        'SELECT wish_status FROM whitebook_wishlist WHERE item_id="'.$item_id.'" and customer_id="'.$customer_id.'"');
        $user_fav = $command->queryAll();
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
        $command = Yii::$app->DB->createCommand(
        'SELECT wish_status FROM whitebook_wishlist WHERE item_id="'.$item_id.'" and customer_id="'.$customer_id.'"');
        $user_fav = $command->queryAll();
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

    public function get_customer_events($customer_id, $limit, $offset, $type)
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
        $command1 = Yii::$app->db->createCommand('SELECT item_id FROM whitebook_wishlist where customer_id="'.$customer_id.'"')->queryAll();

        return $command1;
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
    public static function loadcustomerwishlist($customer_id)
    {
        return $command = Yii::$app->DB->createCommand('SELECT  wvi.item_id,wvi.slug, wvi.item_name, wvi.item_price_per_unit,wv.vendor_name  FROM whitebook_wishlist as ww
			LEFT JOIN whitebook_vendor_item as wvi ON wvi.item_id = ww.item_id
			LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
			WHERE ww.customer_id="'.$customer_id.'" AND
			wvi.item_status="Active" AND wvi.item_for_sale="Yes"
			AND wvi.trash="default" AND type_id=2 AND ww.wish_status=1 AND ww.wish_status=1')->queryAll();
    }
    /* END Get customer wish list */

    public static function vendor_list()
    {
        $today = date('Y-m-d H:i:s');
        $command = Yii::$app->DB->createCommand(
        'SELECT vendor_id,vendor_name FROM whitebook_vendor
		WHERE whitebook_vendor.vendor_Status="Active"
		AND whitebook_vendor.trash="Default"
		AND whitebook_vendor.approve_status="Yes"
		AND whitebook_vendor.package_start_date<="'.$today.'"
		AND whitebook_vendor.package_end_date>="'.$today.'"
		order by vendor_name asc');
        $vendor = $command->queryAll();

        return $vendor;
    }

    public static function get_main_category()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT category_id,category_name,category_url FROM whitebook_category WHERE parent_category_id IS NULL and trash="Default" and category_allow_sale="yes"');
        $general = $command->queryAll();

        return $general;
    }

    public static function get_themes()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT theme_id,theme_name FROM whitebook_theme WHERE trash="Default" and theme_status="Active"');
        $general = $command->queryAll();

        return $general;
    }

    public static function check_email_exist($custemail)
    {
        $sql = 'SELECT customer_org_password FROM whitebook_customer where customer_activation_key!="" and customer_activation_status="1" and trash="default" and customer_email="'.$custemail.'"';
        $command = Yii::$app->DB->createCommand($sql);
        $forget = $command->queryAll();

        return $forget;
    }
    public static function check_user_exist($custemail)
    {
        $command = Yii::$app->DB->createCommand('SELECT customer_activation_key FROM whitebook_customer where customer_email="'.$custemail.'"');
        $forget = $command->queryAll();

        return $forget;
    }

    public function update_datetime_user($key)
    {
        $time = date('Y-m-d H:i:s');
        $sql = 'UPDATE whitebook_customer set modified_datetime="'.$time.'" where customer_activation_key="'.$key.'"';
        $command = Yii::$app->DB->createCommand($sql);
        $customer = $command->execute();

        return $customer;
    }
    public static function check_customer_validtime($key)
    {
        $time = date('Y-m-d H:i:s');
        $sql = "SELECT customer_activation_key FROM `whitebook_customer` WHERE customer_activation_key = '$key' AND TIMESTAMPDIFF(MINUTE,modified_datetime,'$time') <= '1440'";
        $command = Yii::$app->DB->createCommand($sql);
        $forget = $command->queryAll();

        return $forget;
    }
}
