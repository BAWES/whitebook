<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Cms;
use backend\models\Vendoritem;
use backend\models\Vendor;
use backend\models\Category;
use backend\models\Siteinfo;
use backend\models\Faq;
use backend\models\Themes;
use frontend\models\Website;
use frontend\models\Users;
use yii\web\Session;
use yii\db\Query;
use backend\models\Smtp;
class DefaultController extends BaseController
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
        $product_list = $website_model->get_featured_product_id();
        $featured_product = $website_model->get_featured_product();
        $banner = $website_model->get_banner_details();
        $ads = $website_model->get_home_ads();
        $event_type = $website_model->get_event_types();
        $customer_id = Yii::$app->session->get('customer_id');
        $customer_events = array();
        if ($customer_id != '') {
            $customer_events = $website_model->get_customer_events($customer_id);
        }        
        return $this->render('home', [
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
        $customer_id = Yii::$app->params['CUSTOMER_ID'];
        $customer_events = array();
        if ($customer_id != '') {
            $customer_events = $website_model->get_customer_events($customer_id);
        }

        return $this->render('/default/home', [
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

        $directory = $website_model->get_directory_list();
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

        return $this->render('/default/directory', [
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
            $category_model = new Category();
            $website_model = new Website();
            if ($_POST['slug'] != 'All') {
                $categoryid = $category_model->category_value($_POST['slug']);
                $cat_id = $categoryid['category_id'];
                $directory = $website_model->get_search_directory_list($cat_id);

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
            if ($_POST['ajaxdata'] == '0') {
                return $this->renderPartial('/default/searchdirectory', [
        'directory' => $directory,
        'first_letter' => $result, ]);
            } else {
                return $this->renderPartial('/default/searchresponsedirectory', [
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
            $customer_id = Yii::$app->params['CUSTOMER_ID'];
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

            $sql1 = 'select wvi.item_price_per_unit,wi.image_path, wvi.item_price_per_unit,wvi.item_id, wvi.item_name,wvi.slug,wvi.category_id, wv.vendor_name ,count(*) as total FROM whitebook_vendor_item as wvi
      LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
      LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
      WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
      and wvi.item_for_sale="Yes"  AND wi.module_type = "vendor_item" AND (wvi.category_id  = ("'.$cat_id.'") or wvi.subcategory_id  = ("'.$cat_id.'") or wvi.child_category  = ("'.$cat_id.'") or wvi.item_name LIKE "%'.$search.'%" or wv.vendor_name LIKE "%'.$search.'%") Group By wi.item_id';

            $imageData = Yii::$app->db->createCommand($sql1)->queryAll();
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

            if ($customer_id == '') {
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
                        $k = $k.'<li><a href='.Url::toRoute('/search-result/').$url.'>'.$cat[$i].'</a></li>';
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
                        $k = $k.'<li><a href='.Url::toRoute('/search-result/').$url2.'>'.$ven_name[$i].'</a></li>';
                    }
                }
            }

            if (!empty($item_details)) {
                foreach ($item_details as $i) {
                    if (!empty($i['item_name'])) {
                        $url3 = str_replace(' ', '-', $i['item_name']);
                        $k = $k.'<li><a href='.Url::toRoute('/search-result/').$url3.'>'.$i['item_name'].'</a></li>';
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
            $customer_id = Yii::$app->params['CUSTOMER_ID'];
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
        $themes = Yii::$app->db->createCommand('SELECT wt.theme_id,wt.slug,wt.theme_name FROM `whitebook_vendor_item_theme` as wvit
          LEFT JOIN `whitebook_theme` as wt ON FIND_IN_SET(wt.theme_id,wvit.theme_id)
          WHERE wt.theme_status = "Active" AND wvit.vendor_id = "'.$vendor_details[0]['vendor_id'].'" GROUP BY wt.theme_id')->queryAll();

            $vendorData = Yii::$app->db->createCommand('select wi.image_path, wvi.item_price_per_unit, wvi.item_name,wvi.slug, wvi.child_category, wvi.item_id, wv.vendor_name FROM whitebook_vendor_item as wvi
          LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
          LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
          LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.category_id
          WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
          and wvi.item_for_sale="Yes" AND wi.module_type = "vendor_item" AND wv.slug="'.$slug.'" Group By wvi.item_id limit 12')->queryAll();

            if ($customer_id == '') {
                return $this->render('/default/vendor_profile', [
              'vendor_detail' => $vendor_details, 'vendor_item_details' => $vendor_item_details, 'themes' => $themes,
              'category' => $main_category, 'vendorData' => $vendorData,
            ]);
            } else {
                $event_limit = 8;
                $wish_limit = 6;
                $offset = 0;
                $type = '';
                $model = new Users();
                $customer_events_list = $model->get_customer_wishlist_details($customer_id);
                $customer_events = $model->get_customer_events($customer_id, $event_limit, $offset, $type);

                return $this->render('/default/vendor_profile', [
              'vendor_detail' => $vendor_details, 'vendor_item_details' => $vendor_item_details, 'themes' => $themes, 'vendorData' => $vendorData,
              'category' => $main_category, 'customer_events' => $customer_events, 'customer_events_list' => $customer_events_list,
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
            $k = Yii::$app->db->createCommand()->insert('whitebook_contacts', [
            'contact_name' => $data['username'],
            'contact_email' => $data['useremail'],
            'created_datetime' => $date,
            'message' => $data['msg'], ])
            ->execute();
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
                $subject = 'WHITEBOOK - Contact enquiry information.';
                $message = 'WHITEBOOK enquiry from user.';
                Yii::$app->newcomponent->sendmail('a.mariyappan88@gmail.com', $subject, $body, $message);
            }
            if ($k) {
                echo '1';
                die;
            } else {
                echo '0';
                die;
            }
        }

        return $this->render('/default/contact', ['faq' => $faq_details]);
    }

    public function actionCmspages($slug = '')
    {
        if ($slug != '') {
            $cms = new Cms();
            $cms_details = $cms->cms_details($slug);
            $seo_content = \Yii::$app->common->SEOdata('cms', 'page_id', $cms_details['page_id'], array('page_name', 'cms_meta_title', 'cms_meta_keywords', 'cms_meta_description'));

            \Yii::$app->view->title = ($seo_content[0]['cms_meta_title']) ? $seo_content[0]['cms_meta_title'] : Yii::$app->params['SITE_NAME'].' | '.$seo_content[0]['page_name'];
            \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => ($seo_content[0]['cms_meta_description']) ? $seo_content[0]['cms_meta_description'] : Yii::$app->params['META_DESCRIPTION']]);
            \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => ($seo_content[0]['cms_meta_keywords']) ? $seo_content[0]['cms_meta_keywords'] : Yii::$app->params['META_KEYWORD']]);

            return $this->render('/default/cmspages', ['title' => $cms_details['page_name'], 'content' => $cms_details['page_content']]);
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
                $loadvendor = Yii::$app->db->createCommand('SELECT DISTINCT vendor_id, vendor_name FROM whitebook_vendor
              WHERE vendor_id IN ( SELECT vendor_id FROM whitebook_vendor_item where category_id ='.$data['cat_id'].')')->queryAll();
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
                  $loadtheme_ids = Yii::$app->db->createCommand('SELECT DISTINCT theme_id FROM whitebook_vendor_item_theme WHERE vendor_id = (
                SELECT GROUP_CONCAT(DISTINCT theme_id) FROM whitebook_vendor_item_theme WHERE vendor_id = '.$data['v_id'].' )')->queryAll();
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
                $customer_id = Yii::$app->params['CUSTOMER_ID'];
                if (Yii::$app->request->isAjax) {
                    $data = Yii::$app->request->post();
                    $condition = 'ww.wish_status=1 and ww.customer_id= "'.$customer_id.'" ';

                    if (!empty($data['c_id'])) {
                        $condition .= ' AND wvi.category_id= "'.$data['c_id'].'" ';
                    }
                    if (!empty($data['v_id'])) {
                        $condition .= ' AND wvi.vendor_id= "'.$data['v_id'].'" ';
                    }
                    if (!empty($data['a_id'])) {
                        $condition .= ' AND wvi.item_for_sale="'.$data['a_id'].'"';
                    }
                    if (!empty($data['t_id'])) {
                        $condition .= ' AND FIND_IN_SET('.$data['t_id'].' , wvt.theme_id)';
                    }
                    $sql = 'SELECT  wvi.item_id, wvi.item_name, wvi.item_price_per_unit,wv.vendor_name  FROM whitebook_wishlist as ww
                LEFT JOIN whitebook_vendor_item as wvi ON wvi.item_id = ww.item_id
                LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
                LEFT JOIN whitebook_vendor_item_theme as wvt ON wvt.item_id = wvi.item_id
                WHERE '.$condition.' group by wvi.item_id';
                    $wishlist = Yii::$app->DB->createCommand($sql)->queryAll();

                    return $this->renderPartial('/users/user_wish_list', ['wishlist' => $wishlist]);
                }
            }
    public function actionLoadeventlist()
    {
        $customer_id = Yii::$app->params['CUSTOMER_ID'];
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $event = $data['event_name'];
            if ($event == 'all') {
                $command = Yii::$app->DB->createCommand("SELECT event_name,event_id,event_date,event_type,slug
                    FROM whitebook_events
                    INNER JOIN whitebook_event_type
                    ON whitebook_events.event_type=whitebook_event_type.type_name
                    WHERE whitebook_event_type.trash='default' and whitebook_events.customer_id='".$customer_id."'");
                $user_event_list = $command->queryAll();

                return $this->renderPartial('/users/user_event_list', ['user_event_list' => $user_event_list]);
            }
            $command = Yii::$app->DB->createCommand("SELECT event_name,event_id,event_date,event_type,slug
                    FROM whitebook_events
                    INNER JOIN whitebook_event_type
                    ON whitebook_events.event_type=whitebook_event_type.type_name
                    WHERE whitebook_event_type.trash='default' and whitebook_events.event_type='".$event."'and whitebook_events.customer_id='".$customer_id."'");
            $user_event_list = $command->queryAll();

            return $this->renderPartial('/users/user_event_list', ['user_event_list' => $user_event_list]);
        }
    }

    public function actionDeleteevent()
    {
        $customer_id = Yii::$app->params['CUSTOMER_ID'];
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (!empty($data['event_id'])) {
                $command = Yii::$app->DB->createCommand("DELETE FROM whitebook_events WHERE event_id='".$data['event_id']."'");
                if ($command->execute()) {
                    $command = Yii::$app->DB->createCommand("SELECT event_name,event_id,event_date,event_type,slug
                          FROM whitebook_events
                          INNER JOIN whitebook_event_type
                          ON whitebook_events.event_type=whitebook_event_type.type_name
                          WHERE whitebook_event_type.trash='default' and whitebook_events.customer_id='".$customer_id."'");
                    $user_event_list = $command->queryAll();

                    return $this->renderPartial('/users/user_event_list', ['user_event_list' => $user_event_list]);
                    die;
                } else {
                    return 0;
                    die;
                }
            }
        }
    }
                    // END wish list manage page load vendorss based on category

                    /*
                    *  BEGIN Cron Job  for if pending items of items table
                    */
                    public function actionPending_items()
                    {
                        $model = Yii::$app->db->createCommand('SELECT item_name FROM `whitebook_vendor_item` WHERE item_approved="Pending" and item_status = "Active" and trash = "Default"');
                        $vendor = $model->queryAll();
                        $i = 1;
                        $message = 'Items waiting for an approval - Pending items <br/><br/>';
                        $message .= '<table class="tftable" border="1">
                      <tr><th>S.No</th><th>Product Names</th></tr>';
                        foreach ($vendor as $key => $value) {
                            $message .= '<tr><td>'.$i.'</td><td>'.$value['item_name'].'</td></tr>';
                            ++$i;
                        }
                        $message .= '</table>';
                        $subject = 'PENDING-ITEMS';
                        $body = 'TWB - PENDING-PRODUCTS';
                        $send = Yii::$app->maincomponent->sendmail('mariyappan@technoduce.com', $subject, $body, $message, 'PENDING-ITEMS');
                    }
                    /*
                    *  END Cron Job  for if pending items of items table
                    */
}
