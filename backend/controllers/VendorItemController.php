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
use common\models\VendorItemSearch;
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
use backend\models\VendorItem;


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
            ->where(new Expression('FIND_IN_SET(:item_id, item_id)'))->addParams([':item_id' => $id])->all();

        $model_question = VendorItemQuestion::find()
            ->where(['item_id'=>$id,'answer_id'=>null,'question_answer_type'=>'selection'])
            ->orwhere(['item_id'=>$id,'question_answer_type'=>'text','answer_id'=>null])
            ->orwhere(['item_id'=>$id,'question_answer_type'=>'image','answer_id'=>null])
            ->asArray()->all();

        $imagedata = Image::find()->where('item_id = :id', [':id' => $id])->orderby(['vendorimage_sort_order'=>SORT_ASC])->all();

        $model = $this->findModel($id);

        $categories = VendorItemToCategory::find()
            ->with('category')
            ->Where(['item_id' => $id])
            ->all();

        $item_type = ItemType::itemtypename($model->type_id);

        $price_values= VendorItemPricing::loadpricevalues($model->item_id);

        return $this->render('view', [
            'model' => $model,
            'categories' => $categories,
            'item_type' => $item_type,
            'price_values' => $price_values,
            'dataProvider1' => $dataProvider1,
            'model_question' => $model_question,
            'imagedata'=>$imagedata,
        ]);
    }

    /**
     * Creates a new Vendoritem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorItem();
        $model->vendor_id = Vendor::getVendor('vendor_id');

        $model1 = new Image();

        $base = Yii::$app->basePath;
        $len = rand(1,1000);
        $itemtype = ItemType::loaditemtype();
        $vendorname = Vendor::loadvendorname();
        
        if($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->item_for_sale = (Yii::$app->request->post()['VendorItem']['item_for_sale'])?'Yes':'No';

            /* BEGIN  Scenario if item for sale is no not required below four fields all empty*/
            if($model->item_for_sale == 'No')
            {
                $model->item_amount_in_stock = '';
                $model->item_default_capacity = '';
                $model->item_minimum_quantity_to_order='';
                $model->item_how_long_to_make='';
            }
            /* END Scenario if item for sale is no not required below four fields */

            // get the max sort order
            $max_sort = VendorItem::find()
                ->select('MAX(`sort`) as sort')
                ->where(['trash' => 'Default', 'vendor_id' => $model->vendor_id])
                ->asArray()
                ->all();
            $sort = ($max_sort[0]['sort'] + 1);
            $model->item_status='Active';
            $model->sort = $sort;

            if($model->save())
            {
                //BEGIN Manage item pricing table
                $itemid = $model->item_id;

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

                $vendoritem_item_price = Yii::$app->request->post('vendoritem-item_price');

                if($vendoritem_item_price) {

                    for($opt=0; $opt < count($vendoritem_item_price['from']); $opt++){
                        $vendor_item_pricing = new VendorItemPricing();
                        $vendor_item_pricing->item_id =  $itemid;
                        $vendor_item_pricing->range_from = $vendoritem_item_price['from'][$opt];
                        $vendor_item_pricing->range_to = $vendoritem_item_price['to'][$opt];
                        $vendor_item_pricing->pricing_price_per_unit = $vendoritem_item_price['price'][$opt];
                        $vendor_item_pricing->save();
                    }

                }
                //END Manage item pricing table

                //add new images
                $images = Yii::$app->request->post('images');

                foreach ($images as $key => $value) {
                    $image = new Image();
                    $image->image_path = $value['image_path'];
                    $image->item_id = $model->item_id;
                    $image->image_user_id = Yii::$app->user->getId();
                    $image->module_type = 'vendor_item';
                    $image->image_user_type = 'admin';
                    $image->vendorimage_sort_order = $value['vendorimage_sort_order'];
                    $image->save();
                }

                //create draft so admin can approve 
                $draft_item = new VendorDraftItem();
                $draft_item->attributes = $model->attributes;
                $draft_item->priority = 'Normal';
                $draft_item->item_archived = 'No';
                $draft_item->item_approved = 'Pending';
                $draft_item->item_status = 'Active';
                $draft_item->trash = 'Default';
                $draft_item->save(false);

                /*  Upload image table End */
                Yii::$app->session->setFlash('success', "Item added successfully. Admin will check and approve it.");

                Yii::info('[New Item added by '. Yii::$app->user->identity->vendor_name .'] '. Yii::$app->user->identity->vendor_name .' created new item '.$model->item_name, __METHOD__);

                return $this->redirect(['index']);
            }
        } else {

            $categories = CategoryPath::find()
                ->select("GROUP_CONCAT(c1.category_name ORDER BY {{%category_path}}.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS category_name, {{%category_path}}.category_id")
                ->leftJoin('whitebook_category c1', 'c1.category_id = whitebook_category_path.path_id')
                ->leftJoin('whitebook_category c2', 'c2.category_id = whitebook_category_path.category_id')
                ->groupBy('{{%category_path}}.category_id')
                ->orderBy('category_name')
                ->asArray()
                ->all();

            return $this->render('create', [
                'model' => $model,
                'model1' => $model1,
                'itemtype' => $itemtype,
                'vendorname' => $vendorname,
                'categories' => $categories
            ]);
        }
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

        //if customer just viewing 
        if(!$model) {
            $model = $this->findModel($id);
        }        
     
        $model1 = new Image();
        $base = Yii::$app->basePath;
        $len = rand(1,1000);
        
        /* question and answer */
        $model_question = VendorItemQuestion::find()
            ->where(['item_id' => $model->item_id,'answer_id' => Null,'question_answer_type' => 'selection'])
            ->orwhere(['item_id' => $model->item_id,'question_answer_type' =>'text', 'answer_id' => Null])
            ->orwhere(['item_id' => $model->item_id,'question_answer_type' =>'image', 'answer_id' => Null])
            ->asArray()
            ->all();

        $itemtype = ItemType::loaditemtype();
        $vendorname = Vendor::loadvendorname();
        $categoryname = Category::vendorcategory(Yii::$app->user->getId());
        
        $loadpricevalues = VendorItemPricing::loadpricevalues($model->item_id);

        //to save VendorItem data to VendorDraftItem
        if(Yii::$app->request->post('VendorItem')) {
            $posted_data = ['VendorDraftItem' => Yii::$app->request->post('VendorItem')];
        }else{
            $posted_data = Yii::$app->request->post();
        }
        
        if ($model->load($posted_data) && $model->save(false)) {

            /* BEGIN  Scenario if item for sale is no not required below four fields all empty*/
            if ($model->item_for_sale == 'No') {
                $model->item_amount_in_stock = '';
                $model->item_default_capacity = '';
                $model->item_minimum_quantity_to_order='';
                $model->item_how_long_to_make='';
            }
            /* END Scenario if item for sale is no not required below four fields */

            /* Vendor make it any changes item status should be deactivaed */
            $model->item_approved = 'Pending';

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

            //remove old images
            Image::deleteAll(['item_id' => $model->item_id]);

            //add new images
            $images = Yii::$app->request->post('images');

            foreach ($images as $key => $value) {
                $image = new Image();
                $image->image_path = $value['image_path'];
                $image->item_id = $model->item_id;
                $image->image_user_id = Yii::$app->user->getId();
                $image->module_type = 'vendor_item';
                $image->image_user_type = 'admin';
                $image->vendorimage_sort_order = $value['vendorimage_sort_order'];
                $image->save();
            }

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
            //END Manage item pricing table

            Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

            Yii::info('[Item Updated] Vendor updated '.$model->item_name.' item information '. $model->item_id, __METHOD__);

            return $this->redirect(['index']);

        } else {

            $categories = CategoryPath::find()
                ->select("GROUP_CONCAT(c1.category_name ORDER BY {{%category_path}}.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS category_name, {{%category_path}}.category_id")
                ->leftJoin('whitebook_category c1', 'c1.category_id = whitebook_category_path.path_id')
                ->leftJoin('whitebook_category c2', 'c2.category_id = whitebook_category_path.category_id')
                ->groupBy('{{%category_path}}.category_id')
                ->orderBy('category_name')
                ->asArray()
                ->all();

            $vendor_item_to_category = VendorItemToCategory::findAll(['item_id' => $model->item_id]);

            return $this->render('update', [
                'model' => $model,
                'itemtype' => $itemtype,
                'vendorname' => $vendorname,
                'categoryname' => $categoryname,
                'guide_images' => Image::findAll(['item_id' => $model->item_id,'module_type'=>'guides']),
                'images' => Image::findAll(['item_id' => $model->item_id,'module_type'=>'vendor_item']),
                'model1' => $model1,
                'loadpricevalues' => $loadpricevalues,
                'model_question' => $model_question,
                'vendor_item_to_category' => $vendor_item_to_category,
                'categories' => $categories
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
        if(!$is_autosave) {
            $errors = VendorItem::validate_item_info($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }
        }
        
        $model = false;

        //if new item 
        if(!$item_id) {

            $vendor_item = new VendorItem();
            $vendor_item->load(['VendorItem' => $posted_data]);
            $vendor_item->vendor_id = Yii::$app->user->getId();
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

        //load posted data to model 
        $model->load(['VendorDraftItem' => $posted_data]);

        //save first step data without validation 
        $model->save(false);

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

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id,
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
        if(!$is_autosave) {
            $errors = VendorItem::validate_item_description($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }
        }
        
        $model = VendorDraftItem::find()
            ->where(['item_id' => $item_id])
            ->one();
    
        //load posted data to model 
        $model->load(['VendorDraftItem' => $posted_data]);

        //save data without validation 
        $model->save(false);

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id
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
        if(!$is_autosave) {
            $errors = VendorItem::validate_item_price($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 
        
        $model = VendorDraftItem::find()
            ->where(['item_id' => $item_id])
            ->one();
    
        //load posted data to model 
        $model->load(['VendorDraftItem' => $posted_data]);

        //save data without validation 
        $model->save(false);

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

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id
        ];
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

    public function actionCheck($image_id)
    {
        $user = Image::findOne($image_id);
        $user->delete();
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

    public function actionImagedelete()
    {
        if(!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();
        $id = explode(',', $data['id']);
        $ids = implode('","', $id);

        $command=Image::deleteAll(['IN','image_id',$ids]);

        if($command){
           echo 'Deleted';  //die;
        }

        foreach($images as $img)
        {
            unlink(Yii::getAlias('@vendor_images').$img);
        }

        $images = explode(',', $data['loc']);

        if(isset($data['scenario']))
        {
            if($data['scenario']=="top"){

                foreach($images as $img) {
                    unlink(Yii::getAlias('@top_category').$img);
                }

                echo 'Deleted';
                die;

            } else if($data['scenario']=="bottom"){

                foreach($images as $img)
                {
                    unlink(Yii::getAlias('@bottom_category').$img);
                }

                echo 'Deleted';
                die;

            } else if($data['scenario']=="home"){

                foreach($images as $img)
                {
                    unlink(Yii::getAlias('@home_ads').$img);
                }

                echo 'Deleted';
                die;
            }
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

    /* Vendor Item Image Drag SORT Order*/
    public function actionImageorder()
    {
        if(!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();

        $i =1;

        foreach($data['id'] as $order=>$value) {
            $ids = explode('images_',$value);
            $command = Image::updateAll(['vendorimage_sort_order' => $i],['image_id'=>$ids[1]]);
            $i++;
        }
    }

    public function actionRenderquestion()
    {
        if(!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();

        $question = VendorItemQuestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

        if($question[0]['question_answer_type']=='image') {
            $answers = VendorItemQuestionGuide::find()->where(['question_id' =>$data['q_id']])->asArray()->all();
        } else {
            $answers = VendorItemQuestionAnswerOption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
        }

        return $this->renderPartial('questionanswer',
            [
                'question' => $question,
                'answers' => $answers
            ]
        );

        die; /* ALL DIE STATEMENT IMPORTANT FOR VENDOR PANEL*/
    }

    public function actionViewrenderquestion()
    {
        if(!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();

        $question = VendorItemQuestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

        if($question[0]['question_answer_type']=='image'){
            $answers = VendorItemQuestionGuide::find()->where(['question_id' =>$data['q_id']])->asArray()->all();
        } else {
            $answers = VendorItemQuestionAnswerOption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
        }

        return $this->renderPartial(
            'viewquestionanswer',
            [
                'question' => $question,
                'answers' => $answers
            ]
        );
    }

    // Delete item image
    public function actionDeleteItemImage()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if (isset($data['key']) &&  $data['key'] != '') {
                $model = Image::findOne(['image_id'=>$data['key'],'module_type'=>'vendor_item']);
                $image_path = $model['image_path'];
                $model->delete();
                VendorItem::deleteFiles($image_path);
                return 1;
            }
        }
    }

    // Delete item type service or rental image
    public function actionDeleteServiceGuideImage()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if (isset($data['key']) &&  $data['key'] != '') {
                $model = Image::findOne(['image_id'=>$data['key'],'module_type'=>'guides']);
                $image_path = $model['image_path'];
                $model->delete();
                VendorItem::deleteFiles($image_path);
                return 1;
            }
        }
    }

    /*
    *   To check Item name
    */
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

    public function actionRenderanswer()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $question = VendorItemQuestion::find()->where('answer_id = "'.$data['q_id'].'"')->asArray()->all();

            $answers = VendorItemQuestionAnswerOption::find()
            ->where(['question_id' => $question[0]['question_id']])
            ->asArray()
            ->all();

            return $this->renderPartial('questionanswer',
                [
                    'question' => $question,
                    'answers' => $answers
                ]
            );
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
}
