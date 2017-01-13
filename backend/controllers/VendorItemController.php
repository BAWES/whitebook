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
use common\models\VendorDraftItem;
use common\models\VendorDraftItemPricing;
use common\models\VendorDraftItemToCategory;
use common\models\VendorDraftImage;
use backend\models\VendorItem;
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
        }
        
        $item_type = ItemType::itemtypename($model->type_id);

        return $this->render('view', [
            'model' => $model,
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
        $item = new VendorItem();
        $item->vendor_id = Vendor::getVendor('vendor_id');
        $item->version = 1;
        $item->hide_from_admin = 1;
        $item->save(false);

        $model = new VendorDraftItem();
        $model->vendor_id = Vendor::getVendor('vendor_id'); 
        $model->item_id = $item->item_id;
        $model->version = 0;
        $model->save(false);

        $itemtype = ItemType::loaditemtype();
        
        $main_categories = Category::find()
            ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
            ->where([
                '{{%category}}.trash' => 'Default',
                '{{%category_path}}.level' => 0
            ])
            ->all();

        return $this->render('create', [
            'model' => $model,
            'itemtype' => $itemtype,
            'main_categories' => $main_categories
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
        $model = VendorDraftItem::find()
            ->where(['item_id' => $id])
            ->one();

        //if not in draft and trying to post updated data

        if(!$model && Yii::$app->request->isPost) {
            $model = new VendorDraftItem();
            $model->attributes = $this->findModel($id)->attributes;
            $model->item_approved = 'Pending';
        }

        //if customer just viewing && not in draft 

        if(!$model) 
        {
            $model = $this->findModel($id);
        }
     
        $itemtype = ItemType::loaditemtype();
        
        //to save VendorItem data to VendorDraftItem
        if(Yii::$app->request->post('VendorItem')) {
            $posted_data = ['VendorDraftItem' => Yii::$app->request->post('VendorItem')];
        }else{
            $posted_data = Yii::$app->request->post();
        }
        
        if ($model->load($posted_data)) {

            //force to generate slug again by removing old slug 
            $model->slug = '';

            //to make draft visible to admin 

            $model->is_ready = 1;
            $model->item_approved = 'Pending';
            $model->save();

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
            //END Manage item pricing table

            Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

            Yii::info('[Item Updated] Vendor updated '.$model->item_name.' item information '. $model->item_id, __METHOD__);

            return $this->redirect(['index']);

        } else {

            $model_name = $model->formName();
            
            if($model_name == 'VendorItem') 
            {
                //create draft 

                $draft = new VendorDraftItem();
                $draft->attributes = $model->attributes;
                $model->item_approved = 'Pending';
                $draft->save();

                //copy draft related data 

                $pricing = VendorItemPricing::loadpricevalues($model->item_id);

                foreach ($pricing as $key => $value) {
                    $vdip = new VendorDraftItemPricing;
                    $vdip->attributes = $value->attributes;
                    $vdip->save();
                }
                
                $images = Image::findAll(['item_id' => $model->item_id]);

                foreach ($images as $key => $value) {
                    $vdi = new VendorDraftImage;
                    $vdi->attributes = $value->attributes;
                    $vdi->save();
                }

                $categories = VendorItemToCategory::findAll(['item_id' => $model->item_id]);

                foreach ($categories as $key => $value) {
                    $dic = new VendorDraftItemToCategory;
                    $dic->attributes = $value->attributes;
                    $dic->save();
                }

                $item_child_categories = VendorItemToCategory::find()
                    ->select('{{%category}}.category_name, {{%category}}.category_id')
                    ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item_to_category}}.category_id')
                    ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
                    ->where([
                        '{{%category}}.trash' => 'Default',
                        '{{%category_path}}.level' => 2,
                        '{{%vendor_item_to_category}}.item_id' => $model->item_id
                    ])
                    ->groupBy('{{%vendor_item_to_category}}.category_id')
                    ->all();

                //display draft instead of item to resolve optimistick lock version issue 

                $model = $draft;

            }
            else //if in draft 
            {
                $pricing = VendorDraftItemPricing::findAll([
                        'item_id' => $model->item_id
                    ]);

                $images = VendorDraftImage::findAll(['item_id' => $model->item_id]);

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
            }        

            //main
            $main_categories = Category::find()
                ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
                ->where([
                    '{{%category}}.trash' => 'Default',
                    '{{%category_path}}.level' => 0
                ])
                ->all();

            return $this->render('update', [
                'model' => $model,
                'itemtype' => $itemtype,
                'images' => $images,
                'pricing' => $pricing,
                'main_categories' => $main_categories,
                'item_child_categories' => $item_child_categories
            ]);
        }
    }

    /**
    * Save item info from update and create page
    *
    * @return json
    */
    public function actionItemInfo() 
    {
        $item_id = Yii::$app->request->post('item_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        //to save VendorItem data to VendorDraftItem
        $posted_data = VendorItem::get_posted_data();

        //validate 
        $errors = VendorItem::validate_item_info($posted_data);

        $model = false;

        //if new item 

        if(!$item_id) {

            $vendor_item = new VendorItem();
            $vendor_item->load(['VendorItem' => $posted_data]);
            $vendor_item->vendor_id = Yii::$app->user->getId();
            $vendor_item->hide_from_admin = 1;
            $vendor_item->save(false);

            $model = new VendorDraftItem();
            $model->item_id = $vendor_item->item_id;
            $model->vendor_id = Yii::$app->user->getId();
            $model->item_approved = 'Pending';
            $model->priority = 'Normal';
            $model->sort = 0;
            $model->item_archived = 'No';
            $model->item_status = 'inactive';
            $model->trash = 'Default';
        }

        //if old item & in draft 

        if(!$model) {
            $model = VendorDraftItem::find()
                ->where(['item_id' => $item_id])
                ->one();            
        }
        
        //if old item & not in draft

        if(!$model) {
            $model = new VendorDraftItem();
            $model->attributes = $this->findModel($item_id)->attributes;
            $model->item_approved = 'Pending';
        }

        //check version 

        if($model->version != $posted_data['version'])
        {
            $errors['version'] = 'You have old version of data, seems like someone have updated item!';
        }   
    
        if($errors) {
            \Yii::$app->response->format = 'json';
            
            return [
                'errors' => $errors
            ];
        }
    
        //load posted data to model 
        $model->load(['VendorDraftItem' => $posted_data]);

        //to make draft invisible to admin 
        $model->is_ready = 0;

        //force to generate slug again by removing old slug 
        $model->slug = '';

        //save first step data without validation 
        try {

            if(!$model->save())
            {                
                if(!$model->version) {
                    $model->version = 0;
                }
                
                $model->save(false);
            }

        } catch (StaleObjectException $e) {
            
            //if model version defined and version not matching  
            if($model->version){

                $errors['version'] = 'You have old version of data, seems like someone have updated item!';

                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];

            //for first time validation, version not defined yet 
            }else{
                $model->save(false);
            }
        }

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

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id,
            'version' => $model->version,
            'edit_url' => Url::to(['vendor-item/update', 'id' => $model->item_id])
        ];
    }

    /**
    * Save item description from update and create page
    *
    * @return json
    */
    public function actionItemDescription() 
    {
        $item_id = Yii::$app->request->post('item_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        //to save VendorItem data to VendorDraftItem
        $posted_data = VendorItem::get_posted_data();

        //validate 
        $errors = VendorItem::validate_item_description($posted_data);
        
        $model = VendorDraftItem::find()
            ->where(['item_id' => $item_id])
            ->one();

        if($model->version != $posted_data['version'])
        {
            $errors['version'] = 'You have old version of data, seems like someone have updated item!';
        }   

        if($errors) {
            \Yii::$app->response->format = 'json';
            
            return [
                'errors' => $errors
            ];
        }
        
        //load posted data to model 
        $model->load(['VendorDraftItem' => $posted_data]);

        //save data without validation 
        try {

            if(!$model->save())
            {                
                if(!$model->version) {
                    $model->version = 0;
                }
                
                $model->save(false);
            }

        } catch (StaleObjectException $e) {
            
            //if model version defined and version not matching  
            if($model->version){

                $errors['version'] = 'You have old version of data, seems like someone have updated item!';

                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];

            //for first time validation, version not defined yet 
            }else{
                $model->save(false);
            }
        }

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id,
            'version' => $model->version
        ];
    }

    /**
    * Save item price from update and create page
    *
    * @return json
    */
    public function actionItemPrice() 
    {   
        $item_id = Yii::$app->request->post('item_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        //to save VendorItem data to VendorDraftItem

        $posted_data = VendorItem::get_posted_data();

        //validate

        $errors = VendorItem::validate_item_price($posted_data);

        $model = VendorDraftItem::find()
            ->where(['item_id' => $item_id])
            ->one();

        if($model->version != $posted_data['version'])
        {
            $errors['version'] = 'You have old version of data, seems like someone have updated item!';
        }   

        if($errors) {
            \Yii::$app->response->format = 'json';
            
            return [
                'errors' => $errors
            ];
        }    
    
        //load posted data to model 

        $model->load(['VendorDraftItem' => $posted_data]);

        //save data without validation 
        try {

            if(!$model->save())
            {                
                if(!$model->version) {
                    $model->version = 0;
                }
                
                $model->save(false);
            }

        } catch (StaleObjectException $e) {
            
            //if model version defined and version not matching  
            if($model->version){

                $errors['version'] = 'You have old version of data, seems like someone have updated item!';

                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];

            //for first time validation, version not defined yet 
            }else{
                $model->save(false);
            }
        }

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

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id,
            'version' => $model->version,
        ];
    }

    /**
    * Validate whole form on for complete button 
    *
    * @return json
    */
    public function actionItemValidate()
    {
        \Yii::$app->response->format = 'json';
        
        $posted_data = VendorItem::get_posted_data();
        
        $item_id = Yii::$app->request->post('item_id');

        $errors = VendorItem::validate_form($posted_data);

        //check version 

        $item = VendorDraftItem::findOne(['item_id' => $item_id]);

        if($item->version != $posted_data['version'])
        {
            $errors['version'] = 'You have old version of data, seems like someone have updated item!';
        }

        if($errors) 
        {            
            return [
                'errors' => $errors
            ];
        }
        else
        {
            return [
                'success' => 1
            ];
        }
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
        VendorDraftItem::deleteAll(['item_id' => $id]); 
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
