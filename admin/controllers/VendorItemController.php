<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\db\StaleObjectException;
use yii\helpers\UploadHandler;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use admin\models\VendorItem;
use admin\models\AccessControlList;
use common\models\VendorItemQuestion;
use admin\models\VendorItemQuestionAnswerOption;
use admin\models\VendorItemQuestionGuide;
use common\models\VendorItemThemes;
use common\models\FeatureGroupItem;
use admin\models\FeatureGroup;
use admin\models\AuthItem;
use admin\models\Vendor;
use admin\models\Themes;
use admin\models\Image;
use admin\models\Category;
use admin\models\PriorityItem;
use common\models\SubCategory;
use common\models\ChildCategory;
use common\models\VendorItemSearch;
use common\models\ItemType;
use common\models\VendorItemPricing;
use common\models\Prioritylog;
use common\models\VendorItemToCategory;
use common\models\CategoryPath;
use common\models\VendorItemCapacityException;
use common\models\CustomerCart;
use common\models\CustomerCartMenuItem;
use common\models\EventItemlink;
use common\models\VendorDraftItem;
use common\models\VendorItemToPackage;
use common\models\Package;
use common\models\VendorItemMenu;
use common\models\VendorItemMenuItem;
use common\models\VendorDraftItemQuestion;
use common\models\UploadForm;
use common\models\VendorItemVideo;

/**
* VendoritemController implements the CRUD actions for VendorItem model.
*/
class VendorItemController extends Controller
{

    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            $url = Yii::$app->urlManager->createUrl(['admin/site/login']);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                //    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => AccessControlList::can()
                    ],
                ],
            ],            
        ];
    }


    /**
    * Lists all VendorItem models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
        $searchModel = new VendorItemSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
    * Displays a single VendorItem model.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionView($id)
    {
        $dataProvider1 = PriorityItem::find()
            ->select(['priority_level','priority_start_date','priority_end_date'])
            ->item($id)
            ->all();

        $model_question = VendorItemQuestion::findAll(['item_id' => $id]);
        
        $imagedata = Image::find()
            ->item($id)
            ->orderby(['vendorimage_sort_order' => SORT_ASC])
            ->all();

        $videos = VendorItemVideo::find()
                ->where(['item_id' => $id])
                ->orderby(['video_sort_order' => SORT_ASC])
                ->all();

        $categories = VendorItemToCategory::find()
            ->with('category')
            ->item($id)
            ->all();

        $arr_menu = VendorItemMenu::find()->item($id)->menu('options')->all();
        
        $arr_addon_menu = VendorItemMenu::find()->item($id)->menu('addons')->all();

        return $this->render('view', [
            'model' => $this->findModel($id), 
            'dataProvider1' => $dataProvider1, 
            'questions' => $model_question,
            'imagedata' => $imagedata,
            'videos' => $videos,
            'categories' => $categories,
            'arr_menu' => $arr_menu,
            'arr_addon_menu' => $arr_addon_menu
        ]);
    }

    /**
    * Creates a new VendorItem model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @return mixed
    */
    public function actionCreate($_u = null)
    {
        $model = new VendorItem();

        $model->scenario = 'ItemInfo';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            //remove all old category 
            VendorItemToCategory::deleteAll(['item_id' => $model->item_id]);

            //add all category
            $category = Yii::$app->request->post('category');

            if(!$category) {
                $category = array();
            }

            foreach($category as $key => $value) {
                $vic = new VendorItemToCategory();
                $vic->item_id = $model->item_id;
                $vic->category_id = $value;
                $vic->save();
            }

            return $this->redirect(['vendor-item/item-description', 'id' => $model->item_id,'_u'=>$_u]);

        }//if model-load 

        //main
        $main_categories = Category::find()
            ->joinCategoryPath()
            ->defaultCategories()
            ->topLevel()
            ->all();

        $sub_categories = Category::find()
            ->joinCategoryPath()
            ->defaultCategories()
            ->categoryPathLevel(1)
            ->all();

        $vendors = ArrayHelper::map(Vendor::findAll(['trash' => 'Default']), 'vendor_id', 'vendor_name');

        return $this->render('steps/item-info', [
            'model' => $model,
            'main_categories' => $main_categories,
            'sub_categories' => $sub_categories,
            'category_model' => new Category(),
            'item_child_categories' => [],
            'vendors' => $vendors            
        ]);    
    }

    /**
    * Updates an existing VendorItem model.
    * If update is successful, the browser will be redirected to the 'view' page.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionUpdate($id, $vid = false)
    {
        //check if in draft 
        $draft = VendorDraftItem::findOne([
                'is_ready' => 1,
                'item_id' => $id
            ]);

        if($draft) {

            Yii::$app->session->setFlash('danger', "This item available in draft. Please approve/reject draft to edit this item.");

            return $this->redirect(['index']);    
        }
        
        $model = $this->findModel($id);

        $model->scenario = 'ItemInfo';

        //force to generate slug again by removing old slug 
        $model->slug = '';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            //remove all old category 
            VendorItemToCategory::deleteAll(['item_id' => $model->item_id]);

            //add all category
            $category = Yii::$app->request->post('category');

            if(!$category) {
                $category = array();
            }

            foreach($category as $key => $value) {
                $vic = new VendorItemToCategory();
                $vic->item_id = $model->item_id;
                $vic->category_id = $value;
                $vic->save();
            }

            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-description', 'id' => $model->item_id]);

        }//if model-load 

        //main
        $main_categories = Category::find()
            ->joinCategoryPath()
            ->defaultCategories()
            ->categoryPathLevel(0)
            ->all();

        $sub_categories = Category::find()
            ->joinCategoryPath()
            ->defaultCategories()
            ->categoryPathLevel(1)
            ->all();

        //child 
        $item_child_categories = VendorItemToCategory::find()
            ->select('{{%category}}.category_name, {{%category}}.category_id')
            ->joinCategory()
            ->joinCategoryPath()
            ->defaultCategory()
            ->categoryPathLevel(2)
            ->item($model->item_id)
            ->groupBy('{{%vendor_item_to_category}}.category_id')
            ->all();

        $vendors = ArrayHelper::map(Vendor::findAll(['trash' => 'Default']), 'vendor_id', 'vendor_name');

        return $this->render('steps/item-info', [
            'model' => $model,
            'main_categories' => $main_categories,
            'sub_categories' => $sub_categories,
            'item_child_categories' => $item_child_categories,
            'category_model' => new Category(),
            'vendors' => $vendors
        ]);         
    }

    /**
    * Save item description from update and create page
    *
    * @return json
    */
    public function actionItemDescription($id, $_u = null)
    {
        $model = $this->findModel($id);

        $model->scenario = 'ItemDescription';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));
  
            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-price', 'id' => $id,'_u'=>$_u]);
        }

        return $this->render('steps/item-description', [
            'model' => $model
        ]);
    }

    /**
    * Save item price from update and create page
    *
    * @return json
    */
    public function actionItemPrice($id, $_u = null)
    {
        $model = $this->findModel($id);

        $model->scenario = 'ItemPrice';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));

            //remove old price chart
            VendorItemPricing::deleteAll('item_id = :item_id', [':item_id' => $model->item_id]);

            //add price chart
            $vendoritem_item_price = Yii::$app->request->post('vendoritem-item_price');

            if($vendoritem_item_price) {

                for($opt=0; $opt < count($vendoritem_item_price['from']); $opt++){
                    $vendor_item_pricing = new VendorItemPricing();
                    $vendor_item_pricing->item_id =  $model->item_id;
                    $vendor_item_pricing->range_from = $vendoritem_item_price['from'][$opt];
                    $vendor_item_pricing->range_to = $vendoritem_item_price['to'][$opt];
                    $vendor_item_pricing->pricing_price_per_unit = $vendoritem_item_price['price'][$opt];
                    $vendor_item_pricing->save();
                }
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/menu-items', 'id' => $id,'_u'=>$_u]);
        }

        $itemType = ArrayHelper::map(ItemType::findAll(['trash' => 'Default']), 'type_id', 'type_name');

        return $this->render('steps/item-price', [
            'model' => $model,
            'itemPricing' => VendorItemPricing::findAll(['item_id' => $id]),
            'itemType' => $itemType
        ]);
    }

    /**
    * Save menu and menu items from update page
    *
    * @return json
    */
    public function actionMenuItems($id, $_u = null)
    {
        $model = $this->findModel($id);

        $model->scenario = 'MenuItems';

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {            
            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));

            //remove old menu and menu items 

            $old_menues = VendorItemMenu::find()->item($id)->menu('options')->all();

            foreach ($old_menues as $key => $value) {
                VendorItemMenuItem::deleteALL(['menu_id' => $value->menu_id]);
            }

            VendorItemMenu::deleteALL([
                'item_id' => $model->item_id,
                'menu_type' => 'options'
            ]);

            //remove item from cart and cart menu item as item got change 
            
            $cart = CustomerCart::findAll(['item_id' => $model->item_id]);

            foreach ($cart as $key => $value) {
                CustomerCartMenuItem::deleteAll(['cart_id' => $value->cart_id]);
            }
            
            CustomerCart::deleteAll(['item_id' => $model->item_id]);

            //add menu items 

            $menu_items = Yii::$app->request->post('menu_item');
            
            if(!$menu_items) {
                $menu_items = array();
            }

            $menu_id = 0;

            /* This method will allow user to sort menu and menu item easily */

            foreach ($menu_items as $key => $value) {
                
                //if menu 
                if(isset($value['menu_name'])) {

                    $menu = new VendorItemMenu;
                    $menu->attributes = $value;
                    $menu->menu_type = 'options';
                    $menu->item_id = $model->item_id;
                    $menu->save();

                    //update current menu id 
                    $menu_id = $menu->menu_id;

                //if menu item 
                } else {

                    $menu_item = new VendorItemMenuItem;
                    $menu_item->attributes = $value;
                    $menu_item->menu_id = $menu_id;
                    $menu_item->item_id = $model->item_id;
                    $menu_item->save();
                }
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/addon-menu-items', 'id' => $id,'_u'=>$_u]);
        }

        $arr_menu = VendorItemMenu::find()->item($id)->menu('options')->all();

        return $this->render('steps/menu-items', [
            'model' => $model,
            'arr_menu' => $arr_menu
        ]);
    }

    /**
    * Save addon menu and menu items from update page
    *
    * @return json
    */
    public function actionAddonMenuItems($id, $_u = null)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost) 
        {                     
            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));
  
            //remove old menu and menu items 

            $old_menues = VendorItemMenu::find()->item($id)->menu('addons')->all();

            foreach ($old_menues as $key => $value) {
                VendorItemMenuItem::deleteALL(['menu_id' => $value->menu_id]);
            }

            VendorItemMenu::deleteALL([
                'item_id' => $model->item_id,
                'menu_type' => 'addons'
            ]);

            //remove item from cart and cart menu item as item got change 
            
            $cart = CustomerCart::findAll(['item_id' => $model->item_id]);

            foreach ($cart as $key => $value) {
                CustomerCartMenuItem::deleteAll(['cart_id' => $value->cart_id]);
            }
            
            CustomerCart::deleteAll(['item_id' => $model->item_id]);

            //add menu items 

            $menu_items = Yii::$app->request->post('addon_menu_item');
            
            if(!$menu_items) {
                $menu_items = array();
            }

            $menu_id = 0;

            /* This method will allow user to sort menu and menu item easily */

            foreach ($menu_items as $key => $value) {
                
                //if menu 
                if(isset($value['menu_name'])) {
                    
                    $menu = new VendorItemMenu;
                    $menu->attributes = $value;
                    $menu->menu_type = 'addons';
                    $menu->item_id = $model->item_id;
                    $menu->save();

                    //update current menu id 
                    $menu_id = $menu->menu_id;

                //if menu item 
                } else {

                    $menu_item = new VendorItemMenuItem;
                    $menu_item->attributes = $value;
                    $menu_item->menu_id = $menu_id;
                    $menu_item->item_id = $model->item_id;
                    $menu_item->save();
                }
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-approval', 'id' => $id,'_u'=>$_u]);
        }

        $arr_addon_menu = VendorItemMenu::find()->item($id)->menu('addons')->all();

        return $this->render('steps/addon-menu-items', [
            'model' => $model,
            'arr_addon_menu' => $arr_addon_menu
        ]);
    }

    /**
    * Save item images from update and create page
    *
    * @return json
    */
    public function actionItemApproval($id, $_u = null)
    {
        $model = $this->findModel($id);
    
        $model->scenario = 'ItemApproval';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-images', 'id' => $id,'_u'=>$_u]);
        }

        return $this->render('steps/approval', [
            'model' => $model
        ]);
    }

    /**
    * Save item images from update and create page
    *
    * @return json
    */
    public function actionItemImages($id, $_u = null)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost) 
        {            
            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));

            $images = Yii::$app->request->post('images');

            if(!$images) {
                $images = array();
            }

            $arr_image_path = [];

            foreach ($images as $key => $value) {

                //check if image already added 
                
                $image = Image::find()
                    ->where([
                        'item_id' => $id,
                        'image_path' => $value['image_path']
                    ])
                    ->one();

                if($image) {                
                    $image->image_user_id = Yii::$app->user->getId();
                    $image->vendorimage_sort_order = $value['vendorimage_sort_order'];
                    $image->save();
                } else {
                    $image = new Image();
                    $image->image_path = $value['image_path'];
                    $image->item_id = $id;
                    $image->image_user_id = Yii::$app->user->getId();
                    $image->module_type = 'vendor_item';
                    $image->image_user_type = 'admin';
                    $image->vendorimage_sort_order = $value['vendorimage_sort_order'];
                    $image->save();
                }

                $arr_image_path[] = $value['image_path'];
            }
            
            //remove old images
            if($arr_image_path) {
                Image::deleteAll('item_id=' . $id . ' AND 
                    image_path NOT IN ("'.implode('","', $arr_image_path).'")');
            }else{
                Image::deleteAll('item_id=' . $id);
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-videos', 'id' => $id,'_u'=>$_u]);
        }

        return $this->render('steps/images', [
            'model' => $model
        ]);
    }

    /**
    * Save item videos from update and create page
    */
    public function actionItemVideos($id, $_u = null)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost) 
        {          
            //remove old content 
            VendorItemVideo::deleteAll(['item_id' => $id]);

            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));

            $videos = Yii::$app->request->post('videos');

            if(!$videos) {
                $videos = array();
            }

            //add new content 
            foreach ($videos as $key => $value) 
            {
                $video = new VendorItemVideo();
                $video->item_id = $id;
                $video->video = $value['video'];
                $video->video_sort_order = $value['video_sort_order'];
                $video->save();
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $model->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-questions', 'id' => $id, '_u'=>$_u]);
        }

        return $this->render('steps/videos', [
            'model' => $model
        ]);
    }

    /**
    * Save item themes & groups from update and create page
    *
    * @return json
    */
    public function actionItemThemesGroups($id, $_u = null)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost) 
        {            
            //clear draft 
            VendorDraftItem::clear($this->findDraftModel($model->item_id));

            $vendor_item = Yii::$app->request->post('VendorItem');
            // save themes

            if (empty($vendor_item['themes'])){
                $vendor_item['themes'] = [];
            }
            $arr_theme = [];

            foreach ($vendor_item['themes'] as $value) {

                $themesModel = VendorItemThemes::find()
                    ->where([
                        'item_id' => $id,
                        'theme_id' => $value
                    ])
                    ->one();

                if(!$themesModel) {
                    $themesModel = new VendorItemThemes();
                    $themesModel->item_id = $id;
                    $themesModel->theme_id = $value;
                    $themesModel->save();
                }            

                $arr_theme[] = $value;
            }

            if($arr_theme) {
                VendorItemThemes::deleteAll('item_id = ' . $id . ' AND 
                    theme_id NOT IN ('.implode(',', $arr_theme).')');    
            }else{
                VendorItemThemes::deleteAll('item_id = ' . $id);    
            }
            
            if (empty($vendor_item['groups'])){
                $vendor_item['groups'] = [];
            }

            $arr_group = [];

            foreach ($vendor_item['groups'] as $value) {

                $groupModel = FeatureGroupItem::find()
                    ->where([
                        'item_id' => $id,
                        'group_id' => $value
                    ])
                    ->one();


                if(!$groupModel) {

                    $model = VendorItem::findOne($id);
                    
                    $groupModel = new FeatureGroupItem();
                    $groupModel->item_id = $id;
                    $groupModel->group_id = $value;
                    $groupModel->vendor_id = $model->vendor_id;
                    $groupModel->save();  
                }            

                $arr_group[] = $value;
            }

            if($arr_group) {
                FeatureGroupItem::deleteAll('item_id = ' . $id . ' AND 
                    group_id NOT IN ('.implode(',', $arr_group).')');     
            } else {
                FeatureGroupItem::deleteAll('item_id = ' . $id);     
            }   

            /* packages */ 

            $arr_packages = [];

            if(empty($vendor_item['packages'])) {
                $vendor_item['packages'] = [];
            }

            foreach ($vendor_item['packages'] as $value) {

                $item_to_package = VendorItemToPackage::find()
                    ->where([
                        'item_id' => $id,
                        'package_id' => $value
                    ])
                    ->one();

                if(!$item_to_package) {
                    $item_to_package = new VendorItemToPackage();
                    $item_to_package->item_id = $id;
                    $item_to_package->package_id = $value;
                    $item_to_package->save();
                }            

                $arr_packages[] = $value;
            }

            if($arr_packages) {
                VendorItemToPackage::deleteAll('item_id = ' . $id . ' AND 
                    package_id NOT IN ('.implode(',', $arr_packages).')');     
            } else {
                VendorItemToPackage::deleteAll('item_id = ' . $id);     
            }

            Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $id . ' updated successfully!');

            Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

            return $this->redirect(['index']);
        }

        $model->themes = \yii\helpers\ArrayHelper::map($model->vendorItemThemes, 'theme_id', 'theme_id');
        $model->groups = \yii\helpers\ArrayHelper::map($model->featureGroupItems, 'group_id', 'group_id');
        $model->packages = \yii\helpers\ArrayHelper::map($model->vendorItemToPackage, 'package_id', 'package_id');

        $packages = ArrayHelper::map(Package::find()->all(), 'package_id', 'package_name');
        
        $groups = FeatureGroup::loadfeaturegroup();

        $themes = Themes::find()
                    ->active()
                    ->defaultCategory()
                    ->orderBy('theme_name')
                    ->all();

        $themes = ArrayHelper::map($themes, 'theme_id', 'theme_name');

        return $this->render('steps/themes-groups', [
            'model' => $model,
            'packages' => $packages,
            'groups' => $groups,
            'themes' => $themes
        ]);
    }
    
    /**
    * Deletes an existing VendorItem model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionDelete($id)
    {
        //delete main version 
        $model = $this->findModel($id);        
        VendorItem::clear($model);

        //delete draft 
        $draft = $this->findDraftModel($id);
        VendorDraftItem::clear($draft); 

        Yii::$app->session->setFlash('success', 'Vendor item deleted successfully!');
        return $this->redirect(['index']);
    }

    public function actionCheck($image_id)
    {
        $user = Image::findOne($image_id);
        $user->delete();
    }

    /**
    * Finds the VendorItem model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    *
    * @param string $id
    *
    * @return VendorItem the loaded model
    *
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id)
    {
        if (($model = VendorItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findDraftModel($id)
    {
        return VendorDraftItem::findOne([
            'item_id' => $id, 
            'vendor_id' => Yii::$app->user->getId()
        ]);
    }

    public function actionBlock()
    {
        if (!Yii::$app->request->isAjax) {
            die();
        }

        $data = Yii::$app->request->post();
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');

        $vendor_item_update = VendorItem::findOne($data['id']);
        $vendor_item_update->item_status = $status;
        $vendor_item_update->save(false);

        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    //Approve item 
    public function actionApprove()
    {
        if (!Yii::$app->request->isAjax) {
            die();
        }

        $data = Yii::$app->request->post();

        $command = VendorItem::updateAll(['item_approved' => $data['item_approved']],['item_id' =>$data['keylist']]);

        if ($command) {
            Yii::$app->session->setFlash('success', 'Vendor item approve status changed to "'.$data['item_approved'].'" successfully!');
        } else {
            Yii::$app->session->setFlash('danger', 'Something went wrong');
        }
    }

    /* Vendor item gridview status changes */
    public function actionStatus()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        //$ids = implode('","', $data['keylist']);
        if ($data['status'] == 'Delete') {
            $command = VendorItem::deleteAll(['item_id'=>$data['keylist']]);
            if ($command) {
                Yii::$app->session->setFlash('success', 'Vendor item deleted successfully!');
            } else {
                Yii::$app->session->setFlash('danger', 'Something went wrong');
            }
        } elseif ($data['status'] == 'Reject') {
            $command = VendorItem::updateAll(['item_approved' => "rejected"],['item_id' =>$data['keylist']]);
            if($command) {
                Yii::$app->session->setFlash('success', 'Vendor item rejected successfully!');
            } else {
                Yii::$app->session->setFlash('danger', 'Something went wrong');
            }
        } else {
            $command = VendorItem::updateAll(['item_status' => $data['status']],['item_id' =>$data['keylist']]);
            
            if ($command) {
                Yii::$app->session->setFlash('success', 'Vendor item status updated!');
            } else {
                Yii::$app->session->setFlash('danger', 'Something went wrong');
            }
        }
    }

    public function actionUploadhandler()
    {
        $fileupload = new UploadHandler('', true, '', 23);
    }
    
    public function actionUploadhandler1()
    {
        $fileupload = new UploadHandler('', true, '', 23);
    }

    public function actionRemovequestion()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $command = VendorItemQuestion::deleteAll('question_id='.$data['question_id']);
        if ($command) {
            echo 'Question and answers deleted successfully';
        }
    }

    public function actionSort_vendor_item()
    {
        $request = Yii::$app->request;

        $sort = $request->post('sort_val');
        $item_id = $request->post('item_id');

        $command = VendorItem::findOne($item_id);
        $command->sort = $sort;
        $command->update();

        if ($command->execute()) {
            Yii::$app->session->setFlash('success', 'Item sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

    public function actionAddquestion()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $q_id = array();
            if (isset($data['serial_div']) && $data['serial_div'] != '') {
            if ($data['serial_div'][0]['value'] != '') {
                $exist = VendorItemQuestion::findOne($data['serial_div'][0]['value']);
                if (!empty($exist)) {
                    $command = VendorItemQuestion::findOne($data['serial_div'][0]['value']);
                    $command->question_text = $data['serial_div'][1]['value'];
                    $command->update();
                    $q_id[] = $data['serial_div'][0]['value'];
                } else {
                    $model_question = new VendorItemQuestion();
                    $model_question->item_id = $data['item_id'];
                    $model_question->parent_question_id = 0;
                    $model_question->question_text = $data['serial_div'][1]['value'];
                    $model_question->question_answer_type = $data['serial_div'][2]['value'];
                    if (isset($data['serial_div'][0]['value']) && $data['serial_div'][0]['value'] != 0) {
                        $model_question->answer_id = $data['serial_div'][0]['value'];
                    }
                    $model_question->save();
                    $q_id[] = $model_question->question_id;
                }
                $json['response'] = array('parent_id' => $q_id[0]);
        // If attribute is text
                    if ($data['serial_div'][2]['value'] == 'text') {
                        if (count($data['serial_div']) == 4) {
                            $model_answer_option = new VendorItemQuestionAnswerOption();
                            if (isset($data['serial_div'][3]['value']) && $data['serial_div'][3]['value'] != '') {
                                $model_answer_option->answer_price_added = $data['serial_div'][3]['value'];
                            } else {
                                $model_answer_option->answer_price_added = 0;
                            }
                        } else {
                            $ques_id = $data['serial_div'][3]['value'];
                            $model_answer_option = VendorItemQuestionAnswerOption::findOne($ques_id);
                            if (isset($data['serial_div'][4]['value']) && $data['serial_div'][4]['value'] != '') {
                                $model_answer_option->answer_price_added = $data['serial_div'][4]['value'];
                            } else {
                                $model_answer_option->answer_price_added = 0;
                            }
                        }

                        $model_answer_option->question_id = $q_id[0];
                        $model_answer_option->save();
                    } elseif ($data['serial_div'][2]['value'] == 'selection') {
                        $selection1 = array_slice($data['serial_div'], 3);
                        $qa_values = array_chunk($selection1, 3);
                        $price = $json = $exist = $ans = array();

                        foreach ($qa_values as $key => $value) {
                            if ($value[2]['value'] != '') {
                                $ques_id = $value[2]['value'];
                                $model_answer_option = VendorItemQuestionAnswerOption::findOne($ques_id);
                            } else {
                                $model_answer_option = new VendorItemQuestionAnswerOption();
                            }
                            $model_answer_option->question_id = $q_id[0];
                            $model_answer_option->answer_text = $value[0]['value'];
                            if ($value[1]['value'] == '') {
                                $model_answer_option->answer_price_added = 0;
                            } else {
                                $model_answer_option->answer_price_added = $value[1]['value'];
                            }
                            if ($model_answer_option->save()) {
                                array_push($ans, $model_answer_option->answer_id);
                            }
                        }
                        $json['response'] = array('answers' => $ans, 'parent_id' => $q_id[0]);
                    }
                    echo '['.json_encode($json).']';
                    die;
                }
            }
        }
    }

    // todo remove method if not in use
    public function actionGuideimage()
    {
        $base = Yii::$app->basePath;
        $len = rand(1, 1000);
        $path = $base.'/web/uploads/vendor_images/';//server path

        $item_id = Yii::$app->request->post('item_id');
        $question_id  = Yii::$app->request->post('question_id');

        foreach ($_FILES as $key) {

            if ($key['error'] == UPLOAD_ERR_OK) {
                $name = $len.'_'.$key['name'];
                $temp = $key['tmp_name'];
                $size = ($key['size'] / 1000).'Kb';
                $v = move_uploaded_file($temp, $path.$name);
            } else {
                echo $key['error'];
            }

            // image table
            $image_tbl = new Image;
            $image_tbl->image_path = $name;
            $image_tbl->item_id = $item_id;
            $image_tbl->image_user_id = Yii::$app->user->getId();
            $image_tbl->module_type = 'guides';
            $image_tbl->image_user_type ='admin';
            $image_tbl->vendorimage_sort_order = 0;
            $image_tbl->save();

            $last_id = Yii::$app->db->getLastInsertID();

            // guide image table
            $question_tbl = new VendorItemQuestionGuide;
            $question_tbl->question_id = $question_id;
            $question_tbl->guide_image_id = $last_id;
            $question_tbl->save();
            echo $path.$name;
        }
    }

    // todo remove method if not in use
    public function actionRenderquestion()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $question = VendorItemQuestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

            if ($question[0]['question_answer_type'] == 'image') {
                $answers = VendorItemQuestionGuide::find()->where(['question_id' => $data['q_id']])->asArray()->all();
            } else {
                $answers = VendorItemQuestionAnswerOption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
            }

            return $this->renderPartial('questionanswer', ['question' => $question, 'answers' => $answers]);
        }
    }

    // todo remove method if not in use
    public function actionViewrenderquestion()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $question = VendorItemQuestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

            if ($question[0]['question_answer_type'] == 'image') {
                $answers = VendorItemQuestionGuide::find()->where(['question_id' => $data['q_id']])->asArray()->all();
            } else {
                $answers = VendorItemQuestionAnswerOption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
            }
            return $this->renderPartial('viewquestionanswer', ['question' => $question, 'answers' => $answers]);
            die; /* ALL DIE STATEMENT IMPORTANT FOR VENDOR PANEL*/
        }
    }

    // todo remove method if not in use
    public function actionRenderanswer()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $question = VendorItemQuestion::find()->where('answer_id = "'.$data['q_id'].'"')->asArray()->all();
            $answers = VendorItemQuestionAnswerOption::find()->where(['question_id' => $question[0]['question_id']])->asArray()->all();
            return $this->renderPartial('questionanswer', ['question' => $question, 'answers' => $answers]);
        }
    }

    public function actionGalleryupload($id)
    {
        $base = Yii::$app->basePath;
        $len = rand(1, 1000);
        $model = new Image();

        $imagedata = Image::find()
            ->where('item_id = :id AND module_type = :status', [
                ':id' => $id, ':status' => 'vendor_item'
            ])
            ->orderby(['vendorimage_sort_order' => SORT_ASC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            
            $file = UploadedFile::getInstances($model, 'image_path');

            if ($file) {
                $i = count($imagedata) + 1;
                foreach ($file as $files) {
                    $files->saveAs($base.'/web/uploads/vendor_images/'.$files->baseName.'_'.$len.'.'.$files->extension);
                    $model->image_path = $files->baseName.'_'.$len.'.'.$files->extension;
                    $model->item_id = $id;
                    $model->image_user_id = Yii::$app->user->getId();// no need for validation rule on user_id as you set it yourself
                    $model->image_user_type = 1;
                    // image table
                    $image_tbl = new Image;
                    $image_tbl->image_path = $model->image_path;
                    $image_tbl->item_id = $id;
                    $image_tbl->image_user_id = $model->image_user_id;
                    $image_tbl->module_type = 'vendor_item';
                    $image_tbl->vendorimage_sort_order = $i;
                    $image_tbl->save();
                    ++$i;
                }
            }

            return $this->redirect(['galleryupload?id='.$id]);
        }

        return $this->render('galleryupload', ['model' => $model, 'imagedata' => $imagedata]);
    }

    public function actionItemgallery()
    {
        return $this->render('gallery');
    }

    // todo remove method if not in use
    public function actionSalesguideimage($id = '')
    {
        $base = Yii::$app->basePath;
        $len = rand(1, 1000);
        $model = new VendorItem();
        $model1 = new Image();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if (isset($data['question_id']) &&  $data['question_id'] != '') {
                $guideimageval = VendorItemQuestionGuide::find()->select('guide_image_id')->where('question_id = :id', [':id' => $data['question_id']])->all();

                if (!empty($guideimageval)) {
                    foreach ($guideimageval as $key => $value) {
                        $guide_img[] = $value['guide_image_id'];
                    }
                    $guideimagedata = Image::loadimageids($guide_img);
                }
            }
            $file = UploadedFile::getInstances($model, 'guide_image');

            if ($file) {
                foreach ($file as $files) {
                    $files->saveAs($base.'/web/uploads/guide_images/'.$files->baseName.'_'.$len.'.'.$files->extension);
                    $model1->image_path = $files->baseName.'_'.$len.'.'.$files->extension;
                    $model1->item_id = '001';
                $model1->image_user_id = Yii::$app->user->getId();// no need for validation rule on user_id as you set it yourself
                $model1->image_user_type = 1;

                // image table
                $image_tbl = new Image;
                $image_tbl->image_path = $model->image_path;
                $image_tbl->item_id = '001';
                $image_tbl->image_user_id = $model->image_user_id;
                $image_tbl->module_type = 'sales_guides';
                $image_tbl->vendorimage_sort_order = 0;
                $image_tbl->save();

                $last_id = Yii::$app->db->getLastInsertID();
                $quide_tbl = new VendorItemQuestionGuide;
                $quide_tbl->question_id = $id;
                $quide_tbl->guide_image_id = $last_id;;
                $quide_tbl->save();
                die;
            }
        }
        return $this->renderPartial('salesguide', [
            'model' => $model, 
            'guideimagedata' => (isset($guideimagedata) && is_array($guideimagedata)) ? $guideimagedata : array(), 
            'question_id' => $data['question_id']
        ]);
    }
    }

        // Delete item type sales image

    // todo remove method if not in use
    public function actionDeletesalesimage()
    {
        $model1 = new Image();
        if (Yii::$app->request->isAjax) {
            
            $data = Yii::$app->request->post();

            if (isset($data['key']) &&  $data['key'] != '') {
                $image_path = Image::loadimageids($data['key']);
                unlink(Yii::getAlias('@sales_guide_images').$image_path[0]['image_path']);
                Image::deleteAll('image_id='.$data['key']);
                Vendoritemquestionquide::deleteAll('guide_image_id='.$data['key']);
            }
        }
    }
        // Delete item image
    // todo remove method if not in use
    public function actionDeleteitemimage()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            
            if (isset($data['key']) &&  $data['key'] != '') {
                $imageData = Image::findOne(['image_id'=>$data['key']]);
            
                if ($imageData){
                    VendorItem::deleteFiles($imageData->image_path);
                    echo $imageData->delete();
                }
            }
        }
    }
    // todo remove method if not in use
    // Delete item type service or rental image
    public function actionDeleteserviceguideimage()
    {
        $model1 = new Image();

        if (Yii::$app->request->isAjax) {
        
            $data = Yii::$app->request->post();
        
            if (isset($data['key']) &&  $data['key'] != '') {
                $image_path = Image::loadserviceguideimageids($data['key']);
                unlink(Yii::getAlias('@sales_guide_images').$image_path[0]['image_path']);
                Image::deleteAll('image_id='.$data['key']);
            }
        }
    }

    // todo remove method if not in use
    public function actionItemnamecheck()
    {
        if (!Yii::$app->request->isAjax) {
            Yii::$app->end();
        }

        $data = Yii::$app->request->post();

        $count_query = VendorItem::find()
            ->select('item_name')
            ->where([
                'item_name' => $data['item'],
                'trash' => 'Default'
            ]);

        if ($data['item_id']) {            
            $count_query->andWhere(['!=', 'item_id', $data['item_id']]);
        }

        echo $count_query->count();
    }

    public function actionAddTheme()
    {
        $data = Yii::$app->request->post();

        $model = new Themes();
        $model->scenario = 'insert';        
        $model->theme_name_ar = $data['theme_name_ar'];
        $model->theme_name = strtolower($data['theme_name']);
        $model->theme_status = 'Active';
        $model->trash = 'Default';

        Yii::$app->response->format = 'json';

        if($model->save())
        {
            return [
                'theme_id' => $model->theme_id
            ];    
        }
        else
        {
            return [
                'errors' => $model->getErrors()
            ];
        }        
    }

    public function actionAddGroup()
    {
        $data = Yii::$app->request->post();

        $model = new FeatureGroup();
        $model->group_name_ar = $data['group_name_ar'];
        $model->group_name = strtolower($data['group_name']);
        $model->group_status = 'Active';
        $model->trash = 'Default';

        Yii::$app->response->format = 'json';

        if($model->save())
        {
            return [
                'group_id' => $model->group_id
            ];    
        }
        else
        {
            return [
                'errors' => $model->getErrors()
            ];
        }        
    }

    /**
     * upload croped image 
     * @param base64 image data 
     * @return json containing image url and image name 
     */
    public function actionUploadMenuImage() {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $temp_folder = sys_get_temp_dir().'/'; 

        // Set max execution time 3 minutes.
        set_time_limit(3 * 60); 

        $model = new UploadForm;
        
        if(!$model->validate()) {
            return [
                'error' => 'Invalid file!'
            ];
        }

        $image = UploadedFile::getInstance($model, 'file');

        $image_name = Yii::$app->security->generateRandomString() .'.' .$image->extension;

        $imagine = new \Imagine\Gd\Imagine();

        //resize to 70 x 70 

        $thumbnail = $imagine->open($image->tempName);
        $thumbnail->resize($thumbnail->getSize()->widen(70));
        $thumbnail->save($temp_folder . $image_name); 

        //save thumbnail to s3
        $awsResult = Yii::$app->resourceManager->save(
            null, //file upload object  
            VendorItem::UPLOADFOLDER_MENUITEM_THUMBNAIL . $image_name, // name
            [], //options 
            $temp_folder . $image_name, // source file
            $image->type
        ); 

        if (!$awsResult) {
            return [
                'error' => 'File not uploaded successfully!'
            ];    
        }

        //save original image to s3
        $awsResult = Yii::$app->resourceManager->save(
            $image, //file upload object  
            VendorItem::UPLOADFOLDER_MENUITEM . $image_name// name
        ); 

        //delete temp file 
        unlink($temp_folder . $image_name);
        unlink($image->tempName);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'image_url' => Yii::getAlias("@s3/") . VendorItem::UPLOADFOLDER_MENUITEM_THUMBNAIL . $image_name,
            'image' => $image_name
        ];
    }

    /**
     * upload croped image 
     * @param base64 image data 
     * @return json containing image url and image name 
     */
    public function actionUploadCroppedImage() {

        // Set max execution time 3 minutes.
        set_time_limit(3 * 60); 

        $temp_folder = sys_get_temp_dir().'/'; 

        $image_name = Yii::$app->security->generateRandomString();
        $image_extension = '.jpeg';
        $content_type = 'image/jpeg';

        $base64string = str_replace('data:image/jpeg;base64,', '', Yii::$app->request->post('image'));

        //save to temp folder 
        file_put_contents($temp_folder . $image_name . $image_extension, base64_decode($base64string));

        $imagine = new \Imagine\Gd\Imagine();

        //resize to 530 x 530 
        $image_530 = $imagine->open($temp_folder . $image_name . $image_extension);
        $image_530->resize($image_530->getSize()->widen(530));
        $image_530->save($temp_folder . $image_name . '_530' . $image_extension); 

        //save to s3
        $awsResult = Yii::$app->resourceManager->save(
            null, //file upload object  
            VendorItem::UPLOADFOLDER_530 . $image_name . $image_extension, // name
            [], //options 
            $temp_folder . $image_name . '_530' . $image_extension, // source file
            $content_type
        ); 

        if (!$awsResult) {
            return [
                'error' => 'File not uploaded successfully!'
            ];    
        }

        //resize to 210 x 210 
        $image_210 = $imagine->open($temp_folder . $image_name . $image_extension);
        $image_210->resize($image_210->getSize()->widen(210));
        $image_210->save($temp_folder . $image_name . '_210' . $image_extension);

        //save to s3
        $awsResult = Yii::$app->resourceManager->save(
            null, //file upload object  
            VendorItem::UPLOADFOLDER_210 . $image_name . $image_extension, // name
            [], //options 
            $temp_folder . $image_name . '_210' . $image_extension, // source file
            $content_type
        ); 
        
        if (!$awsResult) {
            return [
                'error' => 'File not uploaded successfully!'
            ];    
        }

        //delete temp file 530 & 210 /7 original 
        unlink($temp_folder . $image_name . '_530' . $image_extension);
        unlink($temp_folder . $image_name . '_210' . $image_extension);
        unlink($temp_folder . $image_name . $image_extension);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'image_url' => Yii::getAlias("@s3/vendor_item_images_210/") . $image_name . $image_extension,
            'image' => $image_name . $image_extension
        ];
    }

    public function actionAddCategory()
    {
        $model = new Category();

        Yii::$app->response->format = 'json';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        
            $level = 0;
            
            $paths = CategoryPath::find()
                ->where(['category_id' => $model->parent_category_id])
                ->orderBy('level ASC')
                ->all();

            foreach ($paths as $path) {

                $cp = new CategoryPath();
                $cp->category_id = $model->category_id;
                $cp->level = $level;
                $cp->path_id = $path->path_id;
                $cp->save();

                $level++;
            }

            $cp = new CategoryPath();
            $cp->category_id = $model->category_id;
            $cp->path_id = $model->category_id;
            $cp->level = $level;
            $cp->save();

            return [
                'success' => 1,
                'category_id' => $model->category_id,
                'category_name' => $model->category_name
            ];
        } 
        else 
        {
            return [
                'errors' => $model->getErrors()
            ];
        }
    }

    /** 
     * return sub categories for a given category_id
     * @param category_id 
     * @return json containing sub categories for a given category_id 
     */
    public function actionCategoryList()
    {        
        $category_id = Yii::$app->request->post('parent_id');

        Yii::$app->response->format = 'json';

        return [
            'categories' => Category::findAll(['parent_category_id' => $category_id])
        ];
    }

    public function actionItemInventory() {

        $query = VendorItem::find();
        $date = date('Y-m-d');
        $item_id = '';
        $query->where(['trash'=>'Default']);
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post('date')) {
                $date =Yii::$app->request->post('date');
            } else {
                Yii::$app->session->setFlash('danger', 'Please select date');
            }
        }

        if (Yii::$app->request->post('item_id') && Yii::$app->request->post('item_id') != '') {
            $query->andWhere(['item_id' => Yii::$app->request->post('item_id')]);
            $item_id =Yii::$app->request->post('item_id');
        }


        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('inventory',['provider'=>$provider,'date'=>$date,'item_id'=>$item_id]);
    }

    public function actionItemQuestions($id, $_u = null)
    {
        $model = VendorItemQuestion::find()->item($id)->all();
        $item = VendorItem::findOne($id);
        if(Yii::$app->request->isPost) {

            VendorItemQuestion::deleteAll(['item_id' => $id]);
            if (Yii::$app->request->post('VendorDraftItemQuestion')) {
                foreach (Yii::$app->request->post('VendorDraftItemQuestion') as $question) {

                    if ($question['question'] && !empty($question['question'])) {
                        $modelQuestion = new VendorItemQuestion;
                        $modelQuestion->item_id = $id;
                        $modelQuestion->question = $question['question'];
                        $modelQuestion->required = $question['required'];
                        $modelQuestion->created_datetime = date('Y-m-d H:i:s');
                        $modelQuestion->modified_datetime = date('Y-m-d H:i:s');
                        $modelQuestion->trash = 'Default';
                        $modelQuestion->save(false);
                    }
                }
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $item->item_id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($item->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);
            }
            return $this->redirect(['vendor-item/item-themes-groups', 'id' => $id,'_u'=>$_u]);
        }

        return $this->render('steps/item-questions', [
            'item_id' => $id,
            'model' => $model,
        ]);
    }
}
