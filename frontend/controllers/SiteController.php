<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Cms;
use common\models\Vendoritem;
use common\models\Vendoritemthemes;
use frontend\models\Vendor;
use frontend\models\Category;
use common\models\Siteinfo;
use common\models\Events;
use common\models\City;
use common\models\Location;
use common\models\Faq;
use frontend\models\Themes;
use common\models\Featuregroupitem;
use frontend\models\Website;
use frontend\models\Wishlist;
use frontend\models\Users;
use yii\web\Session;
use yii\db\Query;
use common\models\Smtp;
use common\models\CategoryPath;
use frontend\models\Contacts;
use frontend\models\FaqGroup;
use yii\helpers\ArrayHelper;


class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {     
        $website_model = new Website();
        $featuremodel = new Featuregroupitem();
        $product_list = $featuremodel->get_featured_product_id();
        
        $banner = $website_model->get_banner_details();
        
        $featured_product = array();
        
        if (!Yii::$app->user->isGuest) {
            $featured_product = Vendoritem::get_featured_product();
        }
        
        return $this->render('index', [
            'home_slider_alias' => Siteinfo::find()->one()->home_slider_alias,
            'featured_product' => $featured_product,
            'banner' => $banner,
            'key' => '0',
        ]);
    }

    /*
        Activate customer account from email 
    */
    public function actionActivate()
    {
        Yii::$app->session->set('reset_password_mail', '');

        $website_model = new Website();
        $featuremodel = new Featuregroupitem();
        $product_list = $featuremodel->get_featured_product_id();
        
        $banner = $website_model->get_banner_details();
        
        $featured_product = array();
        
        if (!Yii::$app->user->isGuest) {
            $featured_product = Vendoritem::get_featured_product();
        }

        $ads = $website_model->get_home_ads();
        $event_type = $website_model->get_event_types();
        $customer_events = array();

        if (!Yii::$app->user->isGuest) {
            $customer_events = $website_model->getCustomerEvents(Yii::$app->user->identity->customer_id);
        }

        return $this->render('index', [
              'home_slider_alias' => Siteinfo::find()->one()->home_slider_alias, 
              'featured_product' => $featured_product,
              'banner' => $banner,
              'event_type' => $event_type,
              'ads' => $ads,
              'customer_events' => $customer_events,
              'key' => '1',
        ]);
    }

    public function actionVendor_profile($slug,$vendor)
    {
        $website_model = new Website();
        $vendor_details = Vendor::findOne(['slug'=>$vendor]);

        if (empty($vendor_details)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        $data = Yii::$app->request->get();
        $explode = (Yii::$app->request->isAjax) ? '+' : ' ';

        $main_category = Category::find()
            ->where(['category_level'=>'0', 'trash'=>"Default",'category_allow_sale'=>"yes"])
            ->asArray()
            ->all();

        $item_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_for_sale, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%image}}.image_path')
            ->leftJoin(
                '{{%vendor_item_to_category}}',
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            )
            ->leftJoin(
                '{{%vendor_item}}',
                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
            )
            ->leftJoin(
                '{{%vendor_location}}',
                '{{%vendor_item}}.vendor_id = {{%vendor_location}}.vendor_id'
            )
            ->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.vendor_id' => $vendor_details->vendor_id,
            ]);

        if (isset($data['price']) && $data['price'] != '') {

            $price_condition = [];

            foreach (explode($explode, $data['price']) as $key => $value) {
                $arr_min_max = explode('-', $value);
                $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];
            }

            $item_query->andWhere(implode(' OR ', $price_condition));
        }


        //theme filter
        if (isset($data['themes']) && $data['themes'] != '') {

            $theme = explode($explode, $data['themes']);

            foreach ($theme as $key => $value) {
                $themes[] = Themes::find()
                    ->select('theme_id')
                    ->where(['slug' => [$value]])
                    ->asArray()
                    ->all();
            }

            $all_valid_themes = array();

            foreach ($themes as $key => $value) {
                $get_themes = Vendoritemthemes::find()
                    ->select('theme_id, item_id')
                    ->where(['trash' => "Default"])
                    ->andWhere(['theme_id' => [$value[0]['theme_id']]])
                    ->asArray()
                    ->all();

                foreach ($get_themes as $key => $value) {
                    $all_valid_themes[] = $value['item_id'];
                }
            }

            if (count($all_valid_themes)==1) {
                $all_valid_themes = $all_valid_themes[0];
            } else {
                $all_valid_themes = implode('","', $all_valid_themes);
            }

            $item_query->andWhere('{{%vendor_item}}.item_id IN("'.$all_valid_themes.'")');

        }


        $cats = $slug;
        if ($cats != 'all') {
            $categories = [];
            if (isset($data['category']) && $data['category'] != '') {
                $categories = array_merge($categories, explode($explode, $data['category']));
                $cats = implode("','", $categories);
            }
            $q = "{{%category_path}}.path_id IN (select category_id from {{%category}} where slug IN ('$cats') and trash = 'Default')";
            $item_query->andWhere($q);
        }

        $vendor_items = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy('{{%image}}.vendorimage_sort_order', SORT_ASC)
            ->asArray()
            ->all();



        $item_ids = ArrayHelper::map($vendor_items, 'item_id', 'item_id');

        $themes = \common\models\Vendoritemthemes::find()
            ->select(['wt.theme_id','wt.slug','wt.theme_name'])
            ->leftJoin('{{%theme}} AS wt', 'FIND_IN_SET({{%vendor_item_theme}}.theme_id,wt.theme_id)')
            ->Where([
                'wt.theme_status' => 'Active',
                'wt.trash' => 'Default',
                '{{%vendor_item_theme}}.trash' => 'Default'
            ])
            ->andWhere(['IN', '{{%vendor_item_theme}}.item_id', $item_ids])
            ->groupby(['wt.theme_id'])
            ->asArray()
            ->all();

        if (!isset(Yii::$app->user->identity->customer_id)) {
            
            $customer_events_list = [];
            $customer_events = [];

        } else {

            $event_limit = 8;
            $wish_limit = 6;
            $offset = 0;
            $type = '';
            $customer_id = Yii::$app->user->identity->customer_id;

            $model = new Users();
            $customer_events_list = $model->get_customer_wishlist_details($customer_id);
            $customer_events = $model->getCustomerEvents($customer_id, $event_limit, $offset, $type);            
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('@frontend/views/common/items',['items' => $vendor_items,'customer_events_list' => $customer_events_list]);
        }

        return $this->render('/vendor/profile', [
            'vendor_detail' => $vendor_details,
            'vendor_items' => $vendor_items,
            'themes' => $themes,
            'category' => $main_category,
            'slug' => $slug,
            'customer_events' => $customer_events,
            'customer_events_list' => $customer_events_list
        ]);
    }

    public function actionContact()
    {
        if (Yii::$app->request->isAjax) {

            $date = date('Y/m/d');
            $data = Yii::$app->request->post();

            $subject = 'Enquiry from user';

            $model = new Contacts();
            $model->contact_name=$data['username'];
            $model->contact_email=$data['useremail'];
            $model->created_datetime=$date;
            $model->message = $data['msg'];
            $model->subject = $subject;

            $body = '<table>
            <tbody>
            <tr>
            <td><b>Username</b></td>
            <td>'.$data['username'].'</td>
            </tr>
            <tr>
            <td><b>Email-id</b></td>
            <td>'.$data['useremail'].'</td>
            </tr>
            <tr>
            <td><b>Message</b></td>
            <td>'.$data['msg'].'</td>
            </tr>
            </tbody>
            </table>';

            if ($model->save()) {
                Yii::$app->mailer->compose([
                        "html" => "customer/contact-inquiry"
                            ],[
                        "message" => $body,
                        "user" => $data['username']
                    ])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->setSubject($subject)
                    ->send();

                echo '1';
                die;

            } else {

                echo '0';
                die;
            }
        }

        $faq_details = array();

        $faq_groups = ArrayHelper::toArray(FaqGroup::find()->all());

        foreach ($faq_groups as $group) {
            
            $group['faq_list'] = Faq::find()
                ->where(['faq_group_id' => $group['faq_group_id'], 'faq_status' => 'Active', 'trash' => 'Default'])
                ->all();

            $faq_details[] = $group;
        }

        return $this->render('contact', ['faq_details' => $faq_details]);
    }

    public function actionCmspages($slug = '')
    {
        if (!$slug) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $cms = new Cms();
        $cms_details = $cms->cms_details($slug);
        
        $seo_content = Website::SEOdata('cms', 'page_id', $cms_details['page_id'], array('page_name', 'cms_meta_title', 'cms_meta_keywords', 'cms_meta_description'));

        \Yii::$app->view->title = ($seo_content[0]['cms_meta_title']) ? $seo_content[0]['cms_meta_title'] : Yii::$app->params['SITE_NAME'].' | '.$seo_content[0]['page_name'];
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => ($seo_content[0]['cms_meta_description']) ? $seo_content[0]['cms_meta_description'] : Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => ($seo_content[0]['cms_meta_keywords']) ? $seo_content[0]['cms_meta_keywords'] : Yii::$app->params['META_KEYWORD']]);


        if(Yii::$app->language == "en") {
            
            return $this->render('cmspages', [
                'title' => $cms_details['page_name'], 
                'content' => $cms_details['page_content']
            ]);

        } else {

            return $this->render('cmspages', [
                'title' => $cms_details['page_name_ar'], 
                'content' => $cms_details['page_content_ar']
            ]);
        }
    }

    public function actionInfo()
    {
        return $this->render('test');
    }

    public function actionShop()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Shop';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        return $this->render('shop');
    }

    public function actionExperience()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Experience';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_KEYWORD']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        return $this->render('experience');
    }

        // BEGIN wish list manage page load vendorss based on category
    public function actionLoadvendorlist()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();
        
        $loadvendorid = \common\models\Vendoritem::find()
            ->select(['vendor_id'])
            ->Where(['category_id'=>$data['cat_id']])
            ->asArray()
            ->all();
        
        $loadvendor = \common\models\Vendor::find()
            ->select(['DISTINCT(vendor_id)','vendor_name'])
            ->Where(['IN','vendor_id'=>$loadvendorid])
            ->asArray()
            ->all();
        
        foreach ($loadvendor as $key => $value) {
            echo '<option value='.$value['vendor_id'].'>'.$value['vendor_name'].'</option>';
        }
    }
    
    public function actionLoadthemelist()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        $themes = \common\models\Vendoritemthemes::find()
            ->select(['GROUP_CONCAT(DISTINCT(theme_id)) as theme_id'])
            ->Where(['vendor_id'=>$data['v_id']])
            ->asArray()
            ->all();
        
        $loadtheme_ids = array_unique($themes);
        
        $loadthemes = Themes::find()
            ->select('theme_id, theme_name')
            ->where(['theme_id' => $loadtheme_ids[0]['theme_id']])
            ->asArray()
            ->all();

        foreach ($loadthemes as $key => $value) {
            echo '<option value='.$value['theme_id'].'>'.$value['theme_name'].'</option>';
        }
    }
    
    public function actionLoadwishlist()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $customer_id = Yii::$app->user->identity->customer_id;
        
        $data = Yii::$app->request->post();

        $wishlist_query = \frontend\models\Wishlist::find()
            ->select(['{{%wishlist}}.*','{{%vendor}}.vendor_name','{{%vendor_item}}.item_name','{{%vendor_item}}.item_price_per_unit'])
            ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%wishlist}}.item_id')
            ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
            ->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item_theme}}.item_id = {{%vendor_item}}.item_id')
            ->Where([
                '{{%wishlist}}.wish_status' => 1,
                '{{%wishlist}}.customer_id' => $customer_id,
                '{{%vendor_item}}.trash' => 'Default'
            ]);

        if (!empty($data['v_id'])) {
            $wishlist_query->andWhere(['{{%vendor_item}}.vendor_id' => $data['v_id']]);
        }
    
        if (!empty($data['a_id'])) {
            $wishlist_query->andWhere(['{{%vendor_item}}.item_for_sale' => $data['a_id']]);
        }

        if (!empty($data['t_id'])) {
            $wishlist_query->andWhere('FIND_IN_SET ("'.$data['t_id'].'", {{%vendor_item_theme}}.theme_id)');
        }

        $wishlist = $wishlist_query
            ->asArray()
            ->all();
            
        return $this->renderPartial('/users/user_wish_list', [
            'wishlist' => $wishlist
        ]);
    }

    public function actionLoadeventlist()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $customer_id = Yii::$app->user->identity->customer_id;
        
        $data = Yii::$app->request->post();
        
        $event = $data['event_name'];

        if ($event == 'all') {
			
            $user_event_list = \common\models\Events::find()
    			->select(['event_name','event_id','event_date','event_type','slug'])
    			->innerJoin('{{%event_type}} AS et', '{{%events}}.event_type = et.type_name')
    			->Where(['et.trash'=>'default'])
    			->andWhere(['{{%events}}.customer_id'=>$customer_id])
    			->asArray()
    			->all();
        
            return $this->renderPartial('/users/user_event_list', ['user_event_list' => $user_event_list]);
        }
            
        $user_event_list = \common\models\Events::find()
			->select(['event_name','event_id','event_date','event_type','slug'])
			->innerJoin('{{%event_type}} AS et', '{{%events}}.event_type = et.type_name')
			->Where(['et.trash'=>'default'])
			->andWhere(['{{%events}}.event_type'=>$event])
			->andWhere(['{{%events}}.customer_id'=>$customer_id])
			->asArray()
			->all();

        return $this->renderPartial('/users/user_event_list', [
            'user_event_list' => $user_event_list
        ]);
    }

    public function actionDeleteevent()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $customer_id = Yii::$app->user->identity->customer_id;
        
        $data = Yii::$app->request->post();
        
        if (!empty($data['event_id'])) {
			
            $command = Events::deleteAll('event_id='.$data['event_id']);
            
            if ($command) {
                $user_event_list = \common\models\Events::find()
    				->select(['event_name','event_id','event_date','event_type','slug'])
    				->innerJoin('{{%event_type}} AS et', '{{%events}}.event_type = et.type_name')
    				->Where(['et.trash'=>'default'])
    				->andWhere(['{{%events}}.customer_id'=>$customer_id])
    				->asArray()
    				->all();

		        return $this->renderPartial('/users/user_event_list', [
                    'user_event_list' => $user_event_list
                ]);

            } else {
                return 0;
            }
        }
    }

    public function actionArea()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        $area = Location::find()
            ->select('id, location, location_ar')
            ->where(['city_id' => $data['city_id']])
            ->all();

        $options = '<option value="">'.Yii::t('frontend', 'Select').'</option>';
        
        if (!empty($area)) {
            foreach ($area as $key => $val) {
                if(Yii::$app->language == 'en') {
                    $options .=  '<option value="'.$val['id'].'">'.$val['location'].'</option>';
                } else {
                    $options .=  '<option value="'.$val['id'].'">'.$val['location_ar'].'</option>';
                }
            }
        }
        return $options;
    }

    public function actionCity()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        $city = City::find()
            ->select('city_id, city_name, city_name_ar')
            ->where(['country_id' => $data['country_id']])
            ->all();
        
        $options = '<option value="">'.Yii::t('frontend', 'Select').'</option>';
        
        if (!empty($city)) {
            foreach ($city as $key => $val) {
                if(Yii::$app->language == 'en') {
                    $options .=  '<option value="'.$val['city_id'].'">'.$val['city_name'].'</option>';
                }else{
                    $options .=  '<option value="'.$val['city_id'].'">'.$val['city_name_ar'].'</option>';    
                }
            }
        }
        
        return $options;
    }
}



