<?php

namespace backend\controllers;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Expression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\VendorItemThemes;
use common\models\VendorItemQuestion;
use common\models\VendorItemQuestionAnswerOption;
use common\models\VendorItemQuestionGuide;
use common\models\Vendor;
use common\models\Image;
use common\models\Category;
use common\models\SubCategory;
use common\models\ItemType;
use common\models\Themes;
use common\models\FeatureGroup;
use common\models\FeatureGroupItem;
use common\models\PriorityItem;
use common\models\ChildCategory;
use common\models\VendorItemPricing;
use common\models\VendorItemToCategory;
use common\models\CategoryPath;
use common\models\VendorItemCapacityException;
use common\models\CustomerCart;
use common\models\EventItemlink;
use common\models\VendorDraftItemPricing;
use common\models\VendorDraftItemToCategory;
use common\models\VendorDraftImage;
use common\models\VendorDraftItemMenu;
use common\models\VendorDraftItemMenuItem;
use backend\models\VendorItem;
use backend\models\VendorDraftItem;
use backend\models\VendorItemSearch;
use yii\db\StaleObjectException;

/**
 * VendoritemController implements the CRUD actions for Vendoritem model.
 */
class VendorItemController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                //    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Vendoritem models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->session->setFlash('info', 'All changes done by vendor here will reflect on live site after admin approval.');
        
        $searchModel = new VendorItemSearch();

        $dataProvider = $searchModel->searchVendor(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Vendoritem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProvider1 = PriorityItem::find()
            ->select(['priority_level','priority_start_date','priority_end_date'])
            ->where(new Expression('FIND_IN_SET(:item_id, item_id)'))
            ->addParams([':item_id' => $id])
            ->all();

        //check item in draft 

        $model = VendorDraftItem::find()
            ->where(['item_id' => $id])
            ->one();

        if($model) {
            
            $imagedata = VendorDraftImage::find()
                ->where('item_id = :id', [':id' => $model->item_id])
                ->orderby(['vendorimage_sort_order' => SORT_ASC])
                ->all();

            $price_values= VendorDraftItemPricing::loadpricevalues($model->item_id);

            $categories = VendorDraftItemToCategory::find()
                ->with('category')
                ->Where(['item_id' => $model->item_id])
                ->all();

            $arr_menu = VendorDraftItemMenu::findAll([
                'item_id' => $id,
                'menu_type' => 'options'
            ]);

            $arr_addon_menu = VendorDraftItemMenu::findAll([
                'item_id' => $id,
                'menu_type' => 'addons'
            ]);
        }
        else
        {
            $model = $this->findModel($id);

            $imagedata = Image::find()
                ->where('item_id = :id', [':id' => $model->item_id])
                ->orderby(['vendorimage_sort_order' => SORT_ASC])
                ->all();

            $price_values= VendorItemPricing::loadpricevalues($model->item_id);

            $categories = VendorItemToCategory::find()
                ->with('category')
                ->Where(['item_id' => $model->item_id])
                ->all();

            $arr_menu = VendorItemMenu::findAll([
                'item_id' => $id,
                'menu_type' => 'options'
            ]);

            $arr_addon_menu = VendorItemMenu::findAll([
                'item_id' => $id,
                'menu_type' => 'addons'
            ]);
        }
        
        $item_type = ItemType::itemtypename($model->type_id);

        return $this->render('view', [
            'model' => $model,
            'arr_menu' => $arr_menu,
            'arr_addon_menu' => $arr_addon_menu,
            'categories' => $categories,
            'item_type' => $item_type,
            'price_values' => $price_values,
            'dataProvider1' => $dataProvider1,
            'imagedata' => $imagedata,
        ]);
    }

    /**
     * Creates a new Vendoritem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorDraftItem();
     
        $model->scenario = 'ItemInfo';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            $item = new VendorItem();
            $item->attributes = Yii::$app->request->post('VendorDraftItem');
            $item->vendor_id = Yii::$app->user->getId();
            $item->hide_from_admin = 1;
            $item->save(false);

            $model->vendor_id = Yii::$app->user->getId();
            $model->item_id = $item->item_id;
            $model->save(false);

            //remove all old category 
            VendorDraftItemToCategory::deleteAll(['item_id' => $model->item_id]);

            //add all category
            $category = Yii::$app->request->post('category');

            if(!$category) {
                $category = array();
            }

            foreach($category as $key => $value) {
                $vic = new VendorDraftItemToCategory();
                $vic->item_id = $model->item_id;
                $vic->category_id = $value;
                $vic->save();
            }

            return $this->redirect(['vendor-item/item-description', 'id' => $model->item_id]);

        }//if model-load 

        $main_categories = Category::find()
            ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
            ->where([
                '{{%category}}.trash' => 'Default',
                '{{%category_path}}.level' => 0
            ])
            ->all();

        return $this->render('steps/item-info', [
            'model' => $model,
            'main_categories' => $main_categories,
            'item_child_categories' => []
        ]);
    }

    /**
     * Updates an existing VendorItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //check if item in draft

        $model = $this->findDraftModel($id);

        if(!$model) 
        {
            $model = VendorDraftItem::create_from_item($id);
        }

        $model->scenario = 'ItemInfo';

        //force to generate slug again by removing old slug 
        $model->slug = '';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            //force to generate slug again by removing old slug 
            $model->slug = '';

            //remove all old category 
            VendorDraftItemToCategory::deleteAll(['item_id' => $model->item_id]);

            //add all category
            $category = Yii::$app->request->post('category');

            if(!$category) {
                $category = array();
            }

            foreach($category as $key => $value) {
                $vic = new VendorDraftItemToCategory();
                $vic->item_id = $model->item_id;
                $vic->category_id = $value;
                $vic->save();
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                //to make draft visible to admin 

                $model->is_ready = 1;
                $model->item_approved = 'Pending';
                $model->save();

                Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

                Yii::info('[Item Updated] Vendor updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-description', 'id' => $model->item_id]);

        } 

        $item_child_categories = VendorDraftItemToCategory::find()
            ->select('{{%category}}.category_name, {{%category}}.category_id')
            ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_draft_item_to_category}}.category_id')
            ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
            ->where([
                '{{%category}}.trash' => 'Default',
                '{{%category_path}}.level' => 2,
                '{{%vendor_draft_item_to_category}}.item_id' => $model->item_id
            ])
            ->groupBy('{{%vendor_draft_item_to_category}}.category_id')
            ->all();
    
        //main
        $main_categories = Category::find()
            ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
            ->where([
                '{{%category}}.trash' => 'Default',
                '{{%category_path}}.level' => 0
            ])
            ->all();

        return $this->render('steps/item-info', [
            'model' => $model,
            'main_categories' => $main_categories,
            'item_child_categories' => $item_child_categories
        ]);
    }

    /**
    * Save item description from update and create page
    *
    * @return json
    */
    public function actionItemDescription($id) 
    {
        //check if item in draft

        $model = $this->findDraftModel($id);

        if(!$model) 
        {
            $model = VendorDraftItem::create_from_item($id);
        }

        $model->scenario = 'ItemDescription';

        //force to generate slug again by removing old slug 
        $model->slug = '';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                //to make draft visible to admin 

                $model->is_ready = 1;
                $model->item_approved = 'Pending';
                $model->save();

                Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

                Yii::info('[Item Updated] Vendor updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-price', 'id' => $id]);
        }

        $itemType = ArrayHelper::map(ItemType::findAll(['trash' => 'Default']), 'type_id', 'type_name');

        return $this->render('steps/item-description', [
            'model' => $model,
            'itemtype' => $itemType,
        ]);
    }

    /**
    * Save item price from update and create page
    *
    * @return json
    */
    public function actionItemPrice($id) 
    {   
        $model = $this->findDraftModel($id);

        if(!$model) 
        {
            $model = VendorDraftItem::create_from_item($id);
        }

        $model->scenario = 'ItemPrice';

        if($model->load(Yii::$app->request->post()) && $model->save()) {

            //remove old price chart
            VendorDraftItemPricing::deleteAll('item_id = :item_id', [':item_id' => $model->item_id]);

            //add price chart
            $vendoritem_item_price = Yii::$app->request->post('vendoritem-item_price');

            if($vendoritem_item_price) {

                for($opt=0; $opt < count($vendoritem_item_price['from']); $opt++){
                    $vendor_item_pricing = new VendorDraftItemPricing();
                    $vendor_item_pricing->item_id =  $model->item_id;
                    $vendor_item_pricing->range_from = $vendoritem_item_price['from'][$opt];
                    $vendor_item_pricing->range_to = $vendoritem_item_price['to'][$opt];
                    $vendor_item_pricing->pricing_price_per_unit = $vendoritem_item_price['price'][$opt];
                    $vendor_item_pricing->save();
                }
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                //to make draft visible to admin 

                $model->is_ready = 1;
                $model->item_approved = 'Pending';
                $model->save();

                Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

                Yii::info('[Item Updated] Vendor updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/menu-items', 'id' => $id]);
        }

        return $this->render('steps/item-price', [
            'model' => $model,
            'pricing' => VendorDraftItemPricing::findAll(['item_id' => $id])
        ]);
    }
    
     /**
    * Save menu and menu items from update page
    *
    * @return json
    */
    public function actionMenuItems($id) 
    {
        $model = $this->findDraftModel($id);

        if(!$model) 
        {
            $model = VendorDraftItem::create_from_item($id);
        }

        $model->scenario = 'MenuItems';

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            //remove old menu and menu items 
            
            $old_menues = VendorDraftItemMenu::findALL([
                'item_id' => $model->item_id,
                'menu_type' => 'options'
            ]);

            foreach ($old_menues as $key => $value) {
                VendorDraftItemMenuItem::deleteALL(['draft_menu_id' => $value->draft_menu_id]);
            }

            VendorDraftItemMenu::deleteALL([
                'item_id' => $model->item_id,
                'menu_type' => 'options'
            ]);

            //add menu items 

            $menu_items = Yii::$app->request->post('menu_item');
            
            if(!$menu_items) {
                $menu_items = array();
            }

            $draft_menu_id = 0;

            /* This method will allow user to sort menu and menu item easily */

            foreach ($menu_items as $key => $value) {
                
                //if menu 
                if(isset($value['menu_name'])) {

                    $menu = new VendorDraftItemMenu;
                    $menu->attributes = $value;
                    $menu->menu_type = 'options';
                    $menu->item_id = $model->item_id;
                    $menu->save();

                    //update current menu id 
                    $draft_menu_id = $menu->draft_menu_id;

                //if menu item 
                } else {

                    $menu_item = new VendorDraftItemMenuItem;
                    $menu_item->attributes = $value;
                    $menu_item->draft_menu_id = $draft_menu_id;
                    $menu_item->item_id = $model->item_id;
                    $menu_item->save();
                }
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {

                //to make draft visible to admin 

                $model->is_ready = 1;
                $model->item_approved = 'Pending';
                $model->save();

                Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

                Yii::info('[Item Updated] Vendor updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/addon-menu-items', 'id' => $id]);
        }

        $arr_menu = VendorDraftItemMenu::findAll([
            'item_id' => $id,
            'menu_type' => 'options'
        ]);

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
    public function actionAddonMenuItems($id) 
    {
        $model = $this->findDraftModel($id);
        
        if(!$model) 
        {
            $model = VendorDraftItem::create_from_item($id);
        }

        if(Yii::$app->request->isPost) 
        {           
            //remove old menu and menu items 

            $old_menues = VendorDraftItemMenu::findALL([
                'item_id' => $model->item_id,
                'menu_type' => 'addons'
            ]);

            foreach ($old_menues as $key => $value) {
                VendorDraftItemMenuItem::deleteALL(['draft_menu_id' => $value->draft_menu_id]);
            }

            VendorDraftItemMenu::deleteALL([
                'item_id' => $model->item_id,
                'menu_type' => 'addons'
            ]);

            //add menu items 

            $menu_items = Yii::$app->request->post('addon_menu_item');
            
            if(!$menu_items) {
                $menu_items = array();
            }

            $draft_menu_id = 0;

            /* This method will allow user to sort menu and menu item easily */

            foreach ($menu_items as $key => $value) {
                
                //if menu 
                if(isset($value['menu_name'])) {
                    
                    $menu = new VendorDraftItemMenu;
                    $menu->attributes = $value;
                    $menu->menu_type = 'addons';
                    $menu->item_id = $model->item_id;
                    $menu->save();

                    //update current menu id 
                    $draft_menu_id = $menu->draft_menu_id;

                //if menu item 
                } else {

                    $menu_item = new VendorDraftItemMenuItem;
                    $menu_item->attributes = $value;
                    $menu_item->draft_menu_id = $draft_menu_id;
                    $menu_item->item_id = $model->item_id;
                    $menu_item->save();
                }
            }

            $complete = Yii::$app->request->post('complete');

            if($complete) {
                
                //to make draft visible to admin 

                $model->is_ready = 1;
                $model->item_approved = 'Pending';
                $model->save();

                Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

                Yii::info('[Item Updated] Vendor updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                return $this->redirect(['index']);    
            }            

            return $this->redirect(['vendor-item/item-images', 'id' => $id]);
        }

        $arr_addon_menu = VendorDraftItemMenu::findAll([
            'item_id' => $id,
            'menu_type' => 'addons'
        ]);

        return $this->render('steps/addon-menu-items', [
            'model' => $model,
            'arr_addon_menu' => $arr_addon_menu
        ]);
    }

    public function actionItemImages($id) 
    {
        $model = $this->findDraftModel($id);

        if(!$model) 
        {
            $model = VendorDraftItem::create_from_item($id);
        }

        if(Yii::$app->request->isPost) 
        {
            //remove old images
            VendorDraftImage::deleteAll(['item_id' => $model->item_id]);

            //add new images

            $images = Yii::$app->request->post('images');

            if(!$images) {
                $images = [];
            }
            
            foreach ($images as $key => $value) {
                $image = new VendorDraftImage();
                $image->item_id = $model->item_id;
                $image->image_user_id = Yii::$app->user->getId();
                $image->image_path = $value['image_path'];
                $image->vendorimage_sort_order = $value['vendorimage_sort_order'];
                $image->save();
            }

            Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

            Yii::info('[Item Updated] Vendor updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

            //to make draft visible to admin 

            $model->is_ready = 1;
            $model->item_approved = 'Pending';
            $model->save(false);

            return $this->redirect(['index']);    
        }

        return $this->render('steps/images', [
            'model' => $model,
            'images' => VendorDraftImage::findAll(['item_id' => $model->item_id])
        ]);
    }

    /**
     * Deletes an existing VendorItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleteAllFiles();
        VendorItemCapacityException::deleteAll(['item_id' => $id]);
        Image::deleteAll(['item_id' => $id]);
        VendorItemPricing::deleteAll(['item_id' => $id]);
        VendorItemThemes::deleteAll(['item_id' => $id]);
        VendorItemToCategory::deleteAll(['item_id' => $id]);
        CustomerCart::deleteAll(['item_id' => $id]);
        PriorityItem::deleteAll(['item_id' => $id]);
        EventItemlink::deleteAll(['item_id' => $id]);
        FeatureGroupItem::deleteAll(['item_id' => $id]);

        //menu 

        $menues = VendorItemMenu::findAll(['item_id' => $id]);

        foreach ($menues as $key => $menu) {
            VendorItemMenuItem::deleteAll(['menu_id' => $menu->menu_id]);
        }

        VendorItemMenu::deleteAll(['item_id' => $id]);

        //draft menu 

        $menues = VendorDraftItemMenu::findAll(['item_id' => $id]);

        foreach ($menues as $key => $menu) {
            VendorDraftItemMenuItem::deleteAll(['draft_menu_id' => $menu->draft_menu_id]);
        }

        VendorDraftItemMenu::deleteAll(['item_id' => $id]);
        
        //draft related 
        VendorDraftItemPricing::deleteAll(['item_id' => $id]);
        VendorDraftImage::deleteAll(['item_id' => $id]);
        VendorDraftItemToCategory::deleteAll(['item_id' => $id]);

        //draft 
        VendorDraftItem::deleteAll(['item_id' => $id]); 
        
        //main model 
        $model->delete();

        Yii::$app->session->setFlash('success', "Item deleted successfully!");

        return $this->redirect(['index']);
    }

    /**
     * Finds the VendorItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return VendorItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorItem::findOne(['item_id'=>$id,'vendor_id'=>Yii::$app->user->getId()])) !== null) {
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
        if(!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();
        $status = $data['status'] == 'Active' ? 'Deactive' : 'Active';

        $command = VendorItem::updateAll(['item_status' => $status], ['item_id' => $data['id']]);

        if($status == 'Active')
        {
            Yii::$app->session->setFlash('success', "Item status updated!");

            return Url::to('@web/uploads/app_img/active.png');

        } else {

            Yii::$app->session->setFlash('success', "Item status updated!");

            return Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionSort_vendor_item()
    {
        $sort = Yii::$app->request->post('sort_val');
        $item_id = Yii::$app->request->post('item_id');

        $command=VendorItem::updateAll(['sort' => $sort],['item_id= '.$item_id]);

        if($command) {

            Yii::$app->session->setFlash('success', "Item sort order updated successfully!");

            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

    /* Vendor item gridview status changes */
    public function actionStatus()
    {
        if(!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();

        $ids = $data['keylist'];

        if($data['status'] == 'Delete') {

            $command=VendorItem::deleteAll(['IN','item_id',$ids]);

            if($command) {
                Yii::$app->session->setFlash('success', "Item deleted successfully!");
            } else {
                Yii::$app->session->setFlash('danger', "Something went wrong");
            }
        } else if($data['status'] == 'Reject') {

            $command=VendorItem::updateAll(['item_approved' => 'rejected'],['IN','item_id',$ids]);

            if($command) {
                Yii::$app->session->setFlash('success', "Item rejected successfully!");
            } else {
                Yii::$app->session->setFlash('danger', "Something went wrong");
            }

        } else {

            $command=VendorItem::updateAll(['item_status' => $data['status']],['IN','item_id',$ids]);

            if($command) {
                Yii::$app->session->setFlash('success', "Item status updated!");
            } else {
                Yii::$app->session->setFlash('danger', "Something went wrong");
            }
        }
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
        $image_extension = '.png';
        $content_type = 'image/png';

        $base64string = str_replace('data:image/png;base64,', '', Yii::$app->request->post('image'));

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
}
