<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Cms;
use common\models\Vendoritem;
use frontend\models\Vendor;
use frontend\models\Category;
use common\models\Siteinfo;
use common\models\Events;
use common\models\City;
use common\models\Faq;
use frontend\models\Themes;
use common\models\Featuregroupitem;
use frontend\models\Website;
use frontend\models\Wishlist;
use frontend\models\Users;
use yii\web\Session;
use yii\db\Query;
use common\models\Smtp;

class SiteController extends BaseController
{
    public function behaviors()
    {
        return [
          [
            'class' => 'yii\filters\PageCache',
            'only' => ['index'],
            'duration' => 60,
            'variations' => [
              \Yii::$app->language,
            ],
            'dependency' => [
              'class' => 'yii\caching\DbDependency',
              'sql' => 'SELECT item_id,whitebook_vendor_item.slug as slug,item_name,item_price_per_unit,vendor_name FROM whitebook_vendor_item JOIN whitebook_vendor on whitebook_vendor.vendor_id=whitebook_vendor_item.vendor_id JOIN whitebook_category on whitebook_category.category_id=whitebook_vendor_item.category_id WHERE whitebook_vendor_item.item_status="Active"',
              'sql' => 'SELECT * FROM whitebook_slide where trash="Default" and slide_status="Active" order by sort',
              'sql' => 'SELECT type_name,type_id FROM whitebook_event_type',
              'sql' => 'SELECT * FROM `whitebook_social_info`',
            ],
          ],
        ];
    }

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

    public function init()
    {
        parent::init();
        yii::$app->language = 'en-EN';
    }

    public function  beforeAction($action)
    {
        $session = Yii::$app->session;
        return true;
    }

    public function actionIndex()
    {
        
        $website_model = new Website();
        $featuremodel = new Featuregroupitem();
        $product_list = $featuremodel->get_featured_product_id();
        $featured_product = $featuremodel->get_featured_product();
        $banner = $website_model->get_banner_details();
        $ads = $website_model->get_home_ads();
        $event_type = $website_model->get_event_types();
        $customer_events = array();
        if (!Yii::$app->user->isGuest) {
            $customer_events = $website_model->getCustomerEvents(Yii::$app->user->identity->customer_id);
        }
        return $this->render('index', [
          'featured_product' => $featured_product,
          'banner' => $banner,
          'event_type' => $event_type,
          'ads' => $ads,
          'customer_events' => $customer_events,
          'key' => '0',
        ]);
    }

    public function actionActivate()
    {
        Yii::$app->session->set('reset_password_mail', '');
        $website_model = new Website();
        $product_list = $website_model->get_featured_product_id();
        $featured_product = $website_model->get_featured_product();
        $banner = $website_model->get_banner_details();
        $ads = $website_model->get_home_ads();
        $event_type = $website_model->get_event_types();

        $customer_id = Yii::$app->user->identity->customer_id;
        $customer_events = array();

        if ($customer_id != '') {
            $customer_events = $website_model->getCustomerEvents($customer_id);
        }

        return $this->render('home', [
          'featured_product' => $featured_product,
          'banner' => $banner,
          'event_type' => $event_type,
          'ads' => $ads,
          'customer_events' => $customer_events,
          'key' => '1',
    ]);
    }

    public function actionDirectory()
    {
        $website_model = new Website();
        $category_url = Yii::$app->request->get('name');
        $main_category = $website_model->get_main_category();
        if ($category_url != '') {
            $category_id = $website_model->get_category_id($category_url);
        } else {
            $category_id = '';
        }

        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Directory';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);
        $directory = Vendor::get_directory_list();
        $prevLetter = '';
        $result = array();
        foreach ($directory as $d) {
            $firstLetter = substr($d['vname'], 0, 1);
            if ($firstLetter != $prevLetter) {
                $result[] = strtoupper($firstLetter);
            }
            $prevLetter = $firstLetter;
        }
        $result = array_unique($result);

        return $this->render('directory', [
          'category' => $main_category,
          'directory' => $directory,
          'first_letter' => $result,
        ]);
    }

    public function actionSearchdirectory()
    {
        $website_model = new Website();
        $main_category = $website_model->get_main_category();
        if (Yii::$app->request->isAjax) {
            $website_model = new Website();
            if ($_POST['slug'] != 'All') {
                $categoryid = Category::category_value($_POST['slug']);
                $directory = $website_model->get_search_directory_list($categoryid['category_id']);
                $prevLetter = '';
                $result = array();
                foreach ($directory as $d) {
                    $firstLetter = substr($d['vname'], 0, 1);
                    if ($firstLetter != $prevLetter) {
                        $result[] = strtoupper($firstLetter);
                    }
                    $prevLetter = $firstLetter;
                }
                $result = array_unique($result);
            } else {
                $directory = $website_model->get_search_directory_all_list();
                $prevLetter = '';
                $result = array();
                foreach ($directory as $d) {
                    $firstLetter = substr($d['vname'], 0, 1);
                    if ($firstLetter != $prevLetter) {
                        $result[] = strtoupper($firstLetter);
                    }
                    $prevLetter = $firstLetter;
                }
                $result = array_unique($result);
            }
            if ($_POST['ajaxdata'] == 0) {
                return $this->renderPartial('searchdirectory', [
                    'directory' => $directory,
                    'first_letter' => $result, ]);
            } else {
                return $this->renderPartial('searchresponsedirectory', [
                    'directory' => $directory,
                    'first_letter' => $result, ]);
            }
        }
    }

    public function actionSearchresult($search = '')
    {
        if ($search != '') {
            //item type sale
            $sale = 2;
            $search = str_replace('and', '&', $search);
            $search = str_replace('-', ' ', $search);
            $searchlength = strlen($search);
            
            $model = new Category();
            $active_vendors = Vendor::loadvalidvendors();

            if ($searchlength > 1) {
                $cat_item_details = $model->category_search_details($search);
            }

            if (!empty($cat_item_details)) {
                $cat_id = $cat_item_details[0]['category_id'];
            } else {
                $cat_id = '';
            }

            $k = '';
            $slug = '';
            $imageData = '';

        	$imageData = Vendoritem::find()
			->select('{{%vendor_item}}.item_price_per_unit,{{%image}}.image_path,{{%vendor_item}}.item_id,{{%vendor_item}}.item_name,{{%vendor_item}}.slug,{{%vendor_item}}.category_id,{{%vendor}}.vendor_name,count({{%vendor_item}}.item_id) as total')
			->leftJoin('{{%image}}', '{{%image}}.item_id = {{%vendor_item}}.item_id')
			->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
			->Where(['{{%vendor_item}}.trash' => 'Default','{{%vendor_item}}.type_id' => '2','{{%vendor_item}}.trash' => 'Default','{{%vendor_item}}.item_status' => 'Active','{{%vendor_item}}.item_for_sale' => 'Yes','{{%image}}.module_type' => 'vendor_item','{{%vendor_item}}.category_id' => $cat_id,'{{%vendor_item}}.subcategory_id' => $cat_id,'{{%vendor_item}}.child_category' => $cat_id])
			->orWhere(['like','{{%vendor_item}}.item_name',$search])
			->orWhere(['like','{{%vendor}}.vendor_name',$search])
			->groupBy('{{%image}}.item_id')
			->asArray()
			->all();
			
            foreach ($imageData as $data) {
                $k[] = $data['item_id'];
            }

            $themes1 = '';
            $vendor = '';
            if (!empty($k)) {
                $result = Themes::loadthemename_item($k);
                $out1[] = array();
                $out2[] = array();
                foreach ($result as $r) {
                    if (is_numeric($r['theme_id'])) {
                        $out1[] = $r['theme_id'];
                    }
                    if (!is_numeric($r['theme_id'])) {
                        $out2[] = explode(',', $r['theme_id']);
                    }
                }
                $p = array();
                foreach ($out2 as $id) {
                    foreach ($id as $key) {
                        $p[] = $key;
                    }
                }
                if (count($out1)) {
                    foreach ($out1 as $o) {
                        if (!empty($o)) {
                            $p[] = $o;
                        }
                    }
                }
                $p = array_unique($p);
                $themes1 = Themes::load_all_themename($p);

                $vendor = Vendor::loadvendor_item($k);
            }
            $usermodel = new Users();
            if (Yii::$app->user->isGuest) {
                return $this->render('search', ['imageData' => $imageData,
        'themes' => $themes1, 'vendor' => $vendor, 'slug' => $slug, 'search' => $search, ]);
            } else {
                $customer_events_list = $usermodel->get_customer_wishlist_details($customer_id);

                return $this->render('search', ['imageData' => $imageData,
        'themes' => $themes1, 'vendor' => $vendor, 'slug' => $slug, 'customer_events_list' => $customer_events_list, 'search' => $search, ]);
            }
        }
    }

    public function actionSearch()
    {
        if (isset($_POST['search']) && isset($_POST['_csrf'])) {
            $search_data = $_POST['search'];
            $item = new Vendoritem();
            $item_details = $item->vendoritem_search_details($_POST['search']);

            $k = '';
            $slug1 = array();
            $itm = array();
            if (!empty($item_details)) {
                foreach ($item_details as $i) {
                    $slug1[] = $i['wcslug'];
                    $category[] = $i['category_name'];
                }
            }
            if (!empty($slug1)) {
                $slg = array_unique($slug1);
            } else {
                $slg = '';
            }
            if (!empty($category)) {
                $cat = array_unique($category);
            } else {
                $cat = '';
            }
            if (!empty($cat)) {
                for ($i = 0;$i < count($cat); ++$i) {
                    if (!empty($cat[$i])) {
                        $url = str_replace('&', 'and', $cat[$i]);
                        $url = str_replace(' ', '-', $url);
                        $k = $k.'<li><a href='.Url::toRoute('searchresult/').$url.'>'.$cat[$i].'</a></li>';
                    }
                }
            }

            $ven_slug = array();
            $ven_name = array();
            foreach ($item_details as $i) {
                $ven_slug[] = $i['wvslug'];
                $ven_name[] = $i['vendor_name'];
            }
            $ven_slug = array_unique($ven_slug);
            $ven_name = array_unique($ven_name);
            if (!empty($ven_name)) {
                for ($i = 0;$i < count($ven_name); ++$i) {
                    if (!empty($ven_name[$i])) {
                        $url2 = str_replace(' ', '-', $ven_name[$i]);
                        $k = $k.'<li><a href='.Url::toRoute('searchresult/').$url2.'>'.$ven_name[$i].'</a></li>';
                    }
                }
            }

            if (!empty($item_details)) {
                foreach ($item_details as $i) {
                    if (!empty($i['item_name'])) {
                        $url3 = str_replace(' ', '-', $i['item_name']);
                        $k = $k.'<li><a href='.Url::toRoute('searchresult/').$url3.'>'.$i['item_name'].'</a></li>';
                    }
                }

                return '<ul>'.$k.'</ul>';
            } else {
                echo '0';
                die;
            }
        }
    }

    public function actionVendor_profile($slug = '')
    {
        if ($slug != '') {
            $website_model = new Website();
            $vendor_details = $website_model->vendor_details($slug);
            if (empty($vendor_details)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }
            $vendor_item_details = $website_model->vendor_item_details($vendor_details[0]['vendor_id']);
            $main_category = $website_model->get_main_category();

            \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | '.$vendor_details[0]['vendor_name'];
            \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
            \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        // FOR FILTER
			$themes = \common\models\Vendoritemthemes::find()
			->select(['wt.theme_id','wt.slug','wt.theme_name'])
			->leftJoin('{{%theme}} AS wt', 'FIND_IN_SET({{%vendor_item_theme}}.theme_id,wt.theme_id)')
			->Where(['wt.theme_status'=>'Active'])
			->andWhere(['{{%vendor_item_theme}}.vendor_id'=> $vendor_details[0]['vendor_id']])
			->groupby(['wt.theme_id'])
			->asArray()
			->all();

			$vendorData = Vendoritem::find()
			->select(['{{%image}}.image_path','{{%vendor_item}}.item_price_per_unit','{{%vendor_item}}.item_name','{{%vendor_item}}.slug','{{%vendor_item}}.child_category','{{%vendor_item}}.item_id','{{%vendor}}.vendor_name'])
			->leftJoin('{{%image}}', '{{%image}}.item_id = {{%vendor_item}}.item_id')
			->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
			->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
			->Where(['{{%vendor_item}}.trash'=> 'Default','{{%vendor_item}}.item_approved'=> 'Yes','{{%vendor_item}}.item_status'=> 'Active','{{%vendor_item}}.type_id'=> '2','{{%vendor_item}}.item_for_sale'=> 'Yes','{{%image}}.module_type'=> 'vendor_item','{{%vendor}}.slug'=> $slug])
			->groupby(['{{%vendor_item}}.item_id'])
			->asArray()
			->all();
			
            

            if (!isset(Yii::$app->user->identity->customer_id)) {
                return $this->render('vendor_profile', [
              'vendor_detail' => $vendor_details, 'vendor_item_details' => $vendor_item_details, 'themes' => $themes,
              'category' => $main_category, 'vendorData' => $vendorData,'slug'=>$slug,
            ]);
            } else {
                $event_limit = 8;
                $wish_limit = 6;
                $offset = 0;
                $type = '';
                $model = new Users();
                $customer_events_list = $model->get_customer_wishlist_details($customer_id);
                $customer_events = $model->getCustomerEvents($customer_id, $event_limit, $offset, $type);

                return $this->render('vendor_profile', [
              'vendor_detail' => $vendor_details, 'vendor_item_details' => $vendor_item_details, 'themes' => $themes, 'vendorData' => $vendorData,
              'category' => $main_category, 'customer_events' => $customer_events, 'slug'=>$slug, 'customer_events_list' => $customer_events_list,'slug'=>$slug
            ]);
            }
        }
    }

    public function actionContact()
    {
        $faq = new Faq();
        $faq_details = $faq->faq_details();
        if (Yii::$app->request->isAjax) {
            $date = date('Y/m/d');
            $data = Yii::$app->request->post();
            $model = \admin\models\Contacts();
            $model->contact_name=$data['username'];
            $model->contact_email=$data['useremail'];
            $model->created_datetime=$date;
            $model->message=$data['msg'];
            $model->save();
            $db = Yii::$app->db;// or Category::getDb()
            $result = $db->cache(function ($db) use ($id) {
              return Siteinfo::find()->all();
            }, CACHE_TIMEOUT);

            foreach ($model as $key => $val) {
                $mail_id = $val['email_id'];
            }

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

            if (count($model) == 1) {
                Yii::$app->mailer->compose([
                        "html" => "customer/contact-inquiry"
                            ],[
                        "message" => $body,
                        "user" => $data['username']
                    ])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($form['admin_email'])
                    ->setSubject('Enquiry from user')
                    ->send();
            }
            if ($k) {
                echo '1';
                die;
            } else {
                echo '0';
                die;
            }
        }
        return $this->render('contact', ['faq' => $faq_details]);
    }

    public function actionCmspages($slug = '')
    {
        if ($slug != '') {
            $cms = new Cms();
            $cms_details = $cms->cms_details($slug);
            $seo_content = Website::SEOdata('cms', 'page_id', $cms_details['page_id'], array('page_name', 'cms_meta_title', 'cms_meta_keywords', 'cms_meta_description'));

            \Yii::$app->view->title = ($seo_content[0]['cms_meta_title']) ? $seo_content[0]['cms_meta_title'] : Yii::$app->params['SITE_NAME'].' | '.$seo_content[0]['page_name'];
            \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => ($seo_content[0]['cms_meta_description']) ? $seo_content[0]['cms_meta_description'] : Yii::$app->params['META_DESCRIPTION']]);
            \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => ($seo_content[0]['cms_meta_keywords']) ? $seo_content[0]['cms_meta_keywords'] : Yii::$app->params['META_KEYWORD']]);

            return $this->render('cmspages', ['title' => $cms_details['page_name'], 'content' => $cms_details['page_content']]);
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
            if (Yii::$app->request->isAjax) {
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
                die;
            }
        }
          // END wish list manage page load vendorss based on category

          // BEGIN wish list manage page load vendorss based on category
          public function actionLoadthemelist()
          {
              if (Yii::$app->request->isAjax) {
                  $data = Yii::$app->request->post();
                  $themes = \common\models\Vendoritemthemes::find()
					->select(['GROUP_CONCAT(DISTINCT(theme_id)) as theme_id'])
					->Where(['vendor_id'=>$data['v_id']])
					->asArray()
					->all();
					$loadtheme_ids=array_unique($themes);
                  $loadthemes = Themes::find()->select('theme_id, theme_name')->where(['theme_id' => $loadtheme_ids[0]['theme_id']])->asArray()->all();
                  foreach ($loadthemes as $key => $value) {
                      echo '<option value='.$value['theme_id'].'>'.$value['theme_name'].'</option>';
                  }
              }
          }
            // END wish list manage page load vendorss based on category

            // BEGIN wish list manage page load vendorss based on category
        public function actionLoadwishlist()
        {
            $customer_id = Yii::$app->user->identity->customer_id;
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();

   		$condition ='';
		$condition = "'"."1"."'";
		$condition .= " AND ".""."{{%wishlist}}.wish_status"."";
		$condition .= "=";
		$condition .= "'"."1"."'";
		$condition .= " AND ".""."{{%wishlist}}.customer_id"."";
		$condition .= "=";
		$condition .= "'".$customer_id."'";
		$condition .= " AND ".""."{{%vendor_item}}.trash"."";
		$condition .= "=";
		$condition .= "'"."Default"."'";
		if (!empty($data['v_id'])) {
		$condition .= " AND ".""."{{%vendor_item}}.vendor_id"."";
		$condition .= "=";
		$condition .= "'".$data['v_id']."'";
		}
		if (!empty($data['a_id'])) {
		$condition .= " AND ".""."{{%vendor_item}}.item_for_sale"."";
		$condition .= "=";
		$condition .= "'".$data['a_id']."'";
		}
		if (!empty($data['t_id'])) {
		$condition .= " AND FIND_IN_SET ("."'".$data['t_id']."'";
		$condition .= ",";
		$condition .= ""." {{%vendor_item_theme}}.theme_id"."";
		$condition .= ")";
		}
		$wishlist = \frontend\models\Wishlist::find()
					->select(['{{%wishlist}}.*','{{%vendor}}.vendor_name','{{%vendor_item}}.item_name','{{%vendor_item}}.item_price_per_unit'])
					->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%wishlist}}.item_id')
					->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
					->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item_theme}}.item_id = {{%vendor_item}}.item_id')
					->Where($condition)
					->asArray()
					->all();
                    return $this->renderPartial('/users/user_wish_list', ['wishlist' => $wishlist]);
                }
            }
    public function actionLoadeventlist()
    {
        $customer_id = Yii::$app->user->identity->customer_id;
        if (Yii::$app->request->isAjax) {
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
            return $this->renderPartial('/users/user_event_list', ['user_event_list' => $user_event_list]);
        }
    }

    public function actionDeleteevent()
    {
        $customer_id = Yii::$app->user->identity->customer_id;
        if (Yii::$app->request->isAjax) {
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
			        return $this->renderPartial('/users/user_event_list', ['user_event_list' => $user_event_list]);
                    die;
                } else {
                    return 0;
                    die;
                }
            }
        }
    }


   public function actionCity()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $city = City::find()->select('city_id,city_name')->where(['country_id' => $data['country_id']])->all();
        $options = '<option value="">Select</option>';
        if (!empty($city)) {
            foreach ($city as $key => $val) {
                $options .=  '<option value="'.$val['city_id'].'">'.$val['city_name'].'</option>';
            }
        }
        echo $options;
        die;
    }
                    // END wish list manage page load vendorss based on category
}


//SELECT `wvi`.`item_id`, `wvi`.`item_name`, `wvi`.`item_price_per_unit`, `wv`.`vendor_name` FROM `whitebook_wishlist` LEFT JOIN `whitebook_vendor_item` `wvi` ON wvi.item_id = `whitebook_wishlist`.item_id LEFT JOIN `whitebook_vendor` `wv` ON wv.vendor_id = wvi.vendor_id LEFT JOIN `whitebook_vendor_item_theme` `wvt` ON wvt.item_id = wvi.item_id WHERE '1' AND 'wvi.trash'='Default' AND 'wvi.vendor_id'='1' AND 'wvi.item_for_sale'='Yes'
