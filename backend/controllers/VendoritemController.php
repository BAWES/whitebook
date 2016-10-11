<?php
namespace backend\controllers;


use Yii;
use yii\base\Model;
use backend\models\Vendoritem;
use common\models\Vendoritemthemes;
use common\models\Vendoritemquestion;
use common\models\Vendoritemquestionansweroption;
use common\models\Vendoritemquestionguide;
use common\models\Vendor;
use common\models\Image;
use common\models\Category;
use common\models\SubCategory;
use common\models\VendoritemSearch;
use common\models\Itemtype;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Themes;
use common\models\Featuregroup;
use common\models\Featuregroupitem;
use common\models\Priorityitem;
use common\models\ChildCategory;
use common\models\Vendoritempricing;
use common\models\VendorItemToCategory;
use common\models\CategoryPath;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * VendoritemController implements the CRUD actions for Vendoritem model.
 */
class VendoritemController extends Controller
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
        $searchModel = new VendoritemSearch();

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
        $dataProvider1=Priorityitem::find()
            ->select(['priority_level','priority_start_date','priority_end_date'])
            ->where(new Expression('FIND_IN_SET(:item_id, item_id)'))->addParams([':item_id' => $id])->all();

        $model_question = Vendoritemquestion::find()
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

        $item_type = Itemtype::itemtypename($model->type_id);

        $price_values= Vendoritempricing::loadpricevalues($model->item_id);

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
        $model = new Vendoritem();
        $model->vendor_id = Vendor::getVendor('vendor_id');

        $model1 = new Image();

        $base = Yii::$app->basePath;
        $len = rand(1,1000);
        $itemtype = Itemtype::loaditemtype();
        $vendorname = Vendor::loadvendorname();
        
        if($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->item_for_sale = (Yii::$app->request->post()['Vendoritem']['item_for_sale'])?'Yes':'No';

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
            $max_sort = Vendoritem::find()
                ->select('MAX(`sort`) as sort')
                ->where(['trash' => 'Default', 'vendor_id' => $model->vendor_id])
                ->asArray()
                ->all();
            $sort = ($max_sort[0]['sort'] + 1);
            $model->item_status='Deactive';
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

                if(!empty($vendoritem_item_price['from'])) {

                    for($opt=0; $opt < count($vendoritem_item_price['from']); $opt++){
                        $vendor_item_pricing = new Vendoritempricing();
                        $vendor_item_pricing->item_id =  $itemid;
                        $vendor_item_pricing->range_from = $vendoritem_item_price['from'][$opt];
                        $vendor_item_pricing->range_to = $vendoritem_item_price['to'][$opt];
                        $vendor_item_pricing->pricing_price_per_unit = $vendoritem_item_price['price'][$opt];
                        $vendor_item_pricing->save();
                    }

                }
                //END Manage item pricing table

                /* Begin Upload guide image table  */
                $guide_image = UploadedFile::getInstances($model, 'guide_image');

                if ($guide_image) {
                    $i = 0;

                    foreach ($guide_image as $files) {
                        if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if($resize){
                                $newTmpName = $files->tempName . "." . $files->extension;

                                $imagine = new \Imagine\Gd\Imagine();
                                $image = $imagine->open($files->tempName);
                                $image->resize($image->getSize()->widen(210));
                                $image->save($newTmpName);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName;
                            }

                            //Save to S3
                            $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADSALESGUIDE . $filename);

                            if($awsResult){
                                $model->guide_image = $filename;
                            }

                            $image_tbl = new Image();
                            $image_tbl->image_path = $filename;
                            $image_tbl->item_id = $itemid;
                            $image_tbl->module_type = 'guides';
                            $image_tbl->vendorimage_sort_order = $i;
                            $image_tbl->image_user_id = Yii::$app->user->getId();
                            $image_tbl->save();

                            ++$i;
                        }//end of if instanceof yii\web\UploadedFile
                    }//foreach guide_image
                }//if $guide_image

                /* Begin Upload product image table  */
                $product_file = UploadedFile::getInstances($model, 'image_path');

                if($product_file){

                    $i = 0;

                    foreach ($product_file as $files) {
                        if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if($resize){
                                /* Begin Product image resolution 1000 */
                                $newTmpName2 = $files->tempName . "." . $files->extension;
                                $imagine = new \Imagine\Gd\Imagine();
                                $image_1000 = $imagine->open($files->tempName);
                                $image_1000->resize($image_1000->getSize()->widen(1000));
                                $image_1000->save($newTmpName2);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName2;
                                $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_1000 . $filename);

                                /* Begin Product image resolution 530 */
                                $newTmpName1 = $files->tempName . "." . $files->extension;
                                $image_530 = $imagine->open($files->tempName);
                                $image_530->resize($image_530->getSize()->widen(530));
                                $image_530->save($newTmpName1);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName1;
                                $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_530 . $filename);

                                /* Begin Product image resolution 210 */
                                $newTmpName = $files->tempName . "." . $files->extension;
                                $image = $imagine->open($files->tempName);
                                $image->resize($image->getSize()->widen(210));
                                $image->save($newTmpName);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName;

                                //Save to S3
                                $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_210 . $filename);
                            }

                            if($awsResult){
                                $model->image_path = $filename;
                            }
                        }//end if instanceof yii\web\UploadedFile

                        $image_tbl = new Image();
                        $image_tbl->image_path = $filename;
                        $image_tbl->item_id = $model->item_id;
                        $image_tbl->module_type = 'vendor_item';
                        $image_tbl->image_user_type = 'admin';
                        $image_tbl->vendorimage_sort_order = $i;
                        $image_tbl->image_user_id = Yii::$app->user->getId();
                        $image_tbl->save();
                        ++$i;
                   }//foreach product_file
                }//if $product_file

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
     * Updates an existing Vendoritem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

         $model = $this->findModel($id);
         $model1 = new Image();
         $base = Yii::$app->basePath;
         $len = rand(1,1000);
         $item_id=$model->item_id;

        /* question and answer */
        $model_question = Vendoritemquestion::find()
            ->where(['item_id' => $id,'answer_id' => Null,'question_answer_type' => 'selection'])
            ->orwhere(['item_id' => $id,'question_answer_type' =>'text', 'answer_id' => Null])
            ->orwhere(['item_id' => $id,'question_answer_type' =>'image', 'answer_id' => Null])
            ->asArray()
            ->all();

        $itemtype = Itemtype::loaditemtype();
        $vendorname = Vendor::loadvendorname();
        $categoryname = Category::vendorcategory(Yii::$app->user->getId());
        
        $loadpricevalues = Vendoritempricing::loadpricevalues($item_id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $vendor_item = Yii::$app->request->post('Vendoritem');

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

            if ($model->save()) {

                $itemid = $model->item_id;

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

                $file = UploadedFile::getInstances($model, 'image_path');
                /* Begin Upload guide image table  */
                $guide_image = UploadedFile::getInstances($model, 'guide_image');

                if ($guide_image) {
                    $i = 0;
                    foreach ($guide_image as $files) {
                        if($files instanceof yii\web\UploadedFile) {
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if ($resize) {

                                $newTmpName = $files->tempName . "." . $files->extension;

                                $imagine = new \Imagine\Gd\Imagine();
                                $image = $imagine->open($files->tempName);
                                $image->resize($image->getSize()->widen(210));
                                $image->save($newTmpName);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName;
                            }

                            //Save to S3
                            $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADSALESGUIDE . $filename);

                            if($awsResult){
                                $model->guide_image = $filename;
                            }

                            $image_tbl = new Image();
                            $image_tbl->image_path = $filename;
                            $image_tbl->item_id = $itemid;
                            $image_tbl->module_type = 'guides';
                            $image_tbl->vendorimage_sort_order = $i;
                            $image_tbl->image_user_id = Yii::$app->user->getId();
                            $image_tbl->save();
                            ++$i;
                        }
                    }
                }

                /* Begin Upload guide image table  */

                /* Delete item price table records if its available any price for item type rental or service */
                if($model->type_id == 2) {
                    Vendoritempricing::deleteAll('item_id = :item_id', [':item_id' => $model->item_id]);
                }

                /* Begin Upload guide image table  */
                $product_file = UploadedFile::getInstances($model, 'image_path');

                if ($product_file) {

                    $i = 0;

                    foreach ($product_file as $files) {
                        if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if ($resize) {

                                /* Begin Product image resolution 1000 */
                                $newTmpName2 = $files->tempName . "." . $files->extension;
                                $imagine = new \Imagine\Gd\Imagine();
                                $image_1000 = $imagine->open($files->tempName);
                                $image_1000->resize($image_1000->getSize()->widen(1000));
                                $image_1000->save($newTmpName2);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName2;
                                $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_1000 . $filename);

                                /* Begin Product image resolution 530 */
                                $newTmpName1 = $files->tempName . "." . $files->extension;
                                $image_530 = $imagine->open($files->tempName);
                                $image_530->resize($image_530->getSize()->widen(530));
                                $image_530->save($newTmpName1);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName1;
                                $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_530 . $filename);

                                /* Begin Product image resolution 210 */
                                $newTmpName = $files->tempName . "." . $files->extension;
                                $image = $imagine->open($files->tempName);
                                $image->resize($image->getSize()->widen(210));
                                $image->save($newTmpName);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName;

                                //Save to S3
                                $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_210 . $filename);
                            }//if resize

                            if ($awsResult) {
                                $model->image_path = $filename;
                            }
                        }//if instanceof yii\web\UploadedFile

                        $image_tbl = new Image();
                        $image_tbl->image_path = $filename;
                        $image_tbl->item_id = $model->item_id;
                        $image_tbl->module_type = 'vendor_item';
                        $image_tbl->image_user_type = 'admin';
                        $image_tbl->vendorimage_sort_order = $i;
                        $image_tbl->image_user_id = Yii::$app->user->getId();
                        $image_tbl->save();

                       ++$i;
                   }
                }
                /*  Upload image table End */
            }//end model->save()

            //BEGIN Manage item pricing table
            $vendoritem_item_price = Yii::$app->request->post('vendoritem-item_price');

            if(!empty($vendoritem_item_price['from'])) {

                Vendoritempricing::deleteAll('item_id = :item_id', [':item_id' => $item_id]);

                for($opt=0; $opt < count($vendoritem_item_price['from']); $opt++){
                    $vendor_item_pricing = new Vendoritempricing();
                    $vendor_item_pricing->item_id =  $itemid;
                    $vendor_item_pricing->range_from = $vendoritem_item_price['from'][$opt];
                    $vendor_item_pricing->range_to = $vendoritem_item_price['to'][$opt];
                    $vendor_item_pricing->pricing_price_per_unit = $vendoritem_item_price['price'][$opt];
                    $vendor_item_pricing->save();
                }
            }
            //END Manage item pricing table

            Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");

            Yii::info('[Item Updated] Vendor updated '.$model->item_name.' item information '. $id, __METHOD__);

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
                'guide_images' => Image::findAll(['item_id' => $id,'module_type'=>'guides']),
                'images' => Image::findAll(['item_id' => $id,'module_type'=>'vendor_item']),
                'model1' => $model1,
                'loadpricevalues' => $loadpricevalues,
                'model_question' => $model_question,
                'vendor_item_to_category' => $vendor_item_to_category,
                'categories' => $categories
            ]);
        }
    }

    /**
     * Deletes an existing Vendoritem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', "Item deleted successfully!");

        return $this->redirect(['index']);
    }

    public function actionCheck($image_id)
    {
        $user = Image::findOne($image_id);
        $user->delete();
    }

    /**
     * Finds the Vendoritem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Vendoritem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoritem::findOne(['item_id'=>$id,'vendor_id'=>Yii::$app->user->getId()])) !== null) {
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

        $command = Vendoritem::updateAll(['item_status' => $status], ['item_id= '.$data['id']]);

        if($status == 'Active')
        {
            Yii::$app->session->setFlash('success', "Category status updated!");

            return Url::to('@web/uploads/app_img/active.png');

        } else {

            Yii::$app->session->setFlash('success', "Category status updated!");

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

        $command=Vendoritem::updateAll(['sort' => $sort],['item_id= '.$item_id]);

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

            $command=Vendoritem::deleteAll(['IN','item_id',$ids]);

            if($command) {
                Yii::$app->session->setFlash('success', "Item deleted successfully!");
            } else {
                Yii::$app->session->setFlash('danger', "Something went wrong");
            }
        } else if($data['status'] == 'Reject') {

            $command=Vendoritem::updateAll(['item_approved' => 'rejected'],['IN','item_id',$ids]);

            if($command) {
                Yii::$app->session->setFlash('success', "Item rejected successfully!");
            } else {
                Yii::$app->session->setFlash('danger', "Something went wrong");
            }

        } else {

            $command=Vendoritem::updateAll(['item_status' => $data['status']],['IN','item_id',$ids]);

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

        $question = Vendoritemquestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

        if($question[0]['question_answer_type']=='image') {
            $answers = Vendoritemquestionguide::find()->where(['question_id' =>$data['q_id']])->asArray()->all();
        } else {
            $answers = Vendoritemquestionansweroption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
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

        $question = Vendoritemquestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

        if($question[0]['question_answer_type']=='image'){
            $answers = Vendoritemquestionguide::find()->where(['question_id' =>$data['q_id']])->asArray()->all();
        } else {
            $answers = Vendoritemquestionansweroption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
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
                Vendoritem::deleteFiles($image_path);
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
                Vendoritem::deleteFiles($image_path);
                return 1;
            }
        }
    }

    /*
    *   To check Item name
    */
    public function actionItemnamecheck()
    {
        if (!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();

        if ($data['item_id'] == 0) {

            $itemname = Vendoritem::find()->select('item_name')
            ->where(['item_name' => $data['item']])
            ->andwhere(['trash' => 'Default'])
            ->all();

        } else {

            $itemname = Vendoritem::find()->select('item_name')
            ->where(['item_name' => $data['item']])
            ->where(['item_id' => $data['item_id']])
            ->andwhere(['trash' => 'Default'])
            ->all();

            if (count($itemname) > 0) {
                return  $result = 0;
                die;
            } else {
                return  $result = 1;
                die;
            }
        }

        return $result = count($itemname);
    }

    public function actionRenderanswer()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $question = Vendoritemquestion::find()->where('answer_id = "'.$data['q_id'].'"')->asArray()->all();

            $answers = Vendoritemquestionansweroption::find()
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

}
