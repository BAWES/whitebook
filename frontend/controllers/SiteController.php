<?php

namespace frontend\controllers;

use Yii;
use common\models\Cms;
use common\models\VendorItem;
use common\models\Siteinfo;
use common\models\Events;
use common\models\City;
use common\models\Location;
use common\models\Faq;
use frontend\models\Themes;
use common\models\FeatureGroupItem;
use frontend\models\Website;
use common\models\Smtp;
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
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Home';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        $website_model = new Website();
        $featuremodel = new FeatureGroupItem();
        $product_list = $featuremodel->get_featured_product_id();
        
        $banner = $website_model->get_banner_details();
        
        $featured_product = array();
        
        if (!Yii::$app->user->isGuest) {
            $featured_product = VendorItem::get_featured_product();
        }
        
        return $this->render('index', [
            'home_slider_alias' => Siteinfo::info('home_slider_alias'),
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
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Activate';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        Yii::$app->session->set('reset_password_mail', '');

        $website_model = new Website();
        $featuremodel = new FeatureGroupItem();
        $product_list = $featuremodel->get_featured_product_id();
        
        $banner = $website_model->get_banner_details();
        
        $featured_product = array();
        
        if (!Yii::$app->user->isGuest) {
            $featured_product = VendorItem::get_featured_product();
        }

        $ads = $website_model->get_home_ads();
        $event_type = $website_model->get_event_types();
        $customer_events = array();

        if (!Yii::$app->user->isGuest) {
            $customer_events = $website_model->getCustomerEvents(Yii::$app->user->identity->customer_id);
        }

        return $this->render('index', [
              'home_slider_alias' => Siteinfo::info('home_slider_alias'), 
              'featured_product' => $featured_product,
              'banner' => $banner,
              'event_type' => $event_type,
              'ads' => $ads,
              'customer_events' => $customer_events,
              'key' => '1',
        ]);
    }

    public function actionContact()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Contact';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

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
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Info';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

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
        
        $loadvendorid = \common\models\VendorItem::find()
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

        $themes = \common\models\VendorItemThemes::find()
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

    public function actionArea()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

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
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

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



