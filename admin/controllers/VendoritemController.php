<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use common\models\Vendoritem;
use common\models\Vendoritemquestion;
use common\models\Vendoritemquestionansweroption;
use common\models\Vendoritemquestionguide;
use common\models\Vendoritemthemes;
use common\models\Featuregroupitem;
use common\models\Featuregroup;
use common\models\Authitem;
use common\models\Vendor;
use common\models\Themes;
use common\models\Image;
use common\models\Category;
use common\models\SubCategory;
use common\models\ChildCategory;
use common\models\VendoritemSearch;
use common\models\Itemtype;
use common\models\Vendoritempricing;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\UploadHandler;

/**
* VendoritemController implements the CRUD actions for Vendoritem model.
*/
class VendoritemController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            //$this->redirect('login');
            $url = Yii::$app->urlManager->createUrl(['admin/site/login']);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'check', 'itemactive', 'status', 'removequestion', 'sort_vendor_item', 'addquestion', 'renderquestion', 'renderanswer', 'guideimage', 'viewrenderquestion', 'itemgallery', 'uploadhandler', 'uploadhandler1', 'galleryupload', 'salesguideimage', 'deletesalesimage', 'deleteitemimage', 'deleteserviceguideimage', 'itemnamecheck'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
    * Lists all Vendoritem models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '23');
        if (yii::$app->user->can($access)) {
            $searchModel = new VendoritemSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Displays a single Vendoritem model.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionView($id)
    {
        $access = Authitem::AuthitemviewCheck('view', '23');
        if (yii::$app->user->can($access)) {
            $command = \Yii::$app->DB->createCommand('SELECT priority_level,priority_start_date,priority_end_date FROM whitebook_priority_item where FIND_IN_SET('.$id.', item_id)');
            $dataProvider1 = $command->queryall();

            $model_question = Vendoritemquestion::find()
            ->where(['item_id' => $id, 'answer_id' => null, 'question_answer_type' => 'selection'])
            ->orwhere(['item_id' => $id, 'question_answer_type' => 'text', 'answer_id' => null])
            ->orwhere(['item_id' => $id, 'question_answer_type' => 'image', 'answer_id' => null])
            ->asArray()->all();

            $imagedata = Image::find()->where('item_id = :id AND module_type = :status', [':id' => $id, ':status' => 'vendor_item'])->orderby(['vendorimage_sort_order' => SORT_ASC])->all();

            return $this->render('view', [
                'model' => $this->findModel($id), 'dataProvider1' => $dataProvider1, 'model_question' => $model_question, 'imagedata' => $imagedata,
            ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Creates a new Vendoritem model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @return mixed
    */
    public function actionCreate($vid = '')
    {
        $access = Authitem::AuthitemCheck('1', '23');
        if (yii::$app->user->can($access)) {
            $model = new Vendoritem();
            $model_question = new Vendoritemquestion();
            $model1 = new Image();
            $themelist = Themes::loadthemename();

            $grouplist = Featuregroup::loadfeaturegroup();
            $base = Yii::$app->basePath;
            $len = rand(1, 1000);
            $itemtype = Itemtype::loaditemtype();
            $vendorname = Vendor::loadvendorname();
            if ($model->load(Yii::$app->request->post())) {
                $model->item_for_sale = (Yii::$app->request->post()['Vendoritem']['item_for_sale']) ? 'Yes' : 'No';
                /* END Scenario if item for sale is no not required below four fields */
                $max_sort = $model->findBysql("SELECT MAX(`sort`) as sort FROM `whitebook_vendor_item` where trash = 'Default'")->asArray()->all();
                $sort = ($max_sort[0]['sort'] + 1);

                $model->sort = $sort;
                $model->slug = Yii::$app->request->post()['Vendoritem']['item_name'];
                $c_slug1 = strtolower($model->slug);
                $c_slug2 = str_replace(' ', '-', $c_slug1);
                //Make alphanumeric (removes all other characters)
                $c_slug3 = preg_replace("/[^a-z0-9_\s-]/", '', $c_slug2);
                //Convert whitespaces and underscore to dash
                $c_slug4 = preg_replace("/[\s_]/", '-', $c_slug3);
                $model->slug = $c_slug4;
                $chk_item_exist = Yii::$app->db->createCommand('SELECT *  FROM {{%vendor_item}} WHERE trash="Default" and `slug` LIKE "'.$c_slug4.'"')->queryAll();
                if (!empty($chk_item_exist)) {
                    $tbl_vendor = Yii::$app->db->createCommand('Select vendor_name from {{%vendor}} where vendor_id ='.$_POST['Vendoritem']['vendor_id'])->queryOne();
                    $vendorname = str_replace(' ', '-', $tbl_vendor['vendor_name']);
                    $model->slug = $c_slug4.'-'.$vendorname;
                }
                
                if ($model->save()) {

                    $itemid = $model->item_id;

                    //BEGIN Manage item pricing table
                    if (isset($_POST['vendoritem-item_price']['from']) && $_POST['vendoritem-item_price']['from'] != '') {
                        $from = $_POST['vendoritem-item_price']['from'];
                        $to = $_POST['vendoritem-item_price']['to'];
                        $price = $_POST['vendoritem-item_price']['price'];
                        for ($opt = 0;$opt < count($from);++$opt) {
                            $vendor_item_pricing = new Vendoritempricing();
                            $vendor_item_pricing->item_id = $itemid;
                            $vendor_item_pricing->range_from = $from[$opt];
                            $vendor_item_pricing->range_to = $to[$opt];
                            $vendor_item_pricing->pricing_price_per_unit = $price[$opt];
                            $vendor_item_pricing->save();
                        }
                    }
                    //END Manage item pricing table

                    /* Themes table Begin*/
                    if (isset($_POST['Vendoritem']['themes']) && $_POST['Vendoritem']['themes'] != '') {
                        $theme_id = implode(',', $_POST['Vendoritem']['themes']);
                        $vendor_item_themes = new Vendoritemthemes();
                        $vendor_item_themes->item_id = $itemid;
                        $vendor_item_themes->theme_id = $theme_id;
                        $vendor_item_themes->vendor_id = $model['vendor_id'];
                        $vendor_item_themes->save();
                    }
                    /* Themes table End */

                    /* Groups table Begin*/
                    if (isset($_POST['Vendoritem']['groups']) && $_POST['Vendoritem']['groups'] != '') {
                        $group_id = implode(',', $_POST['Vendoritem']['groups']);
                        $feature_group_item = new Featuregroupitem();
                        $feature_group_item->item_id = $itemid;
                        $feature_group_item->group_id = $group_id;
                        $feature_group_item->vendor_id = $model['vendor_id'];
                        $feature_group_item->save();
                    }
                    /* Groups table End */



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

                        $guide_tbl = Yii::$app->db->createCommand()->insert('whitebook_image', [
                            'image_path' => $filename,
                            'item_id' => $itemid,
                            'image_user_id' => Yii::$app->user->getId(),
                            'module_type' => 'guides',
                            'vendorimage_sort_order' => $i, ])
                            ->execute();
                            ++$i;
                            }
                       }
                    }

                   /* Begin Upload guide image table  */
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
                        }
            
                        $image_tbl = Yii::$app->db->createCommand()
                        ->insert('whitebook_image', [
                        'image_path' => $filename,'item_id' => $model->item_id,
                        'image_user_id' => Yii::$app->user->getId(),'module_type' => 'admin',
                        'image_user_type' => 'admin','vendorimage_sort_order' => $i, ])
                        ->execute();++$i;
                       }
                    }
                            /*  Upload image table End */

                            echo Yii::$app->session->setFlash('success', 'Vendor item added successfully!');
                            Yii::info('[New Item] Admin created new item '.addslashes($model->item_name), __METHOD__);
                            if ($model->type_id == 2) {
                                return $this->redirect(['vendoritem/update?id='.$itemid.'&create='.$itemid]);
                            } elseif (isset($_GET['vid']) != '') {
                                return $this->redirect(['vendoritem/view?id='.$_GET['vid']]);
                            } else {
                                return $this->redirect(['vendoritem/view?id='.$model->item_id]);
                            }
                        }
                    } else {
                        return $this->render('create', [
                            'model' => $model, 'model1' => $model1, 'itemtype' => $itemtype, 'vendorname' => $vendorname, 'model_question' => $model_question,
                            'themelist' => $themelist, 'grouplist' => $grouplist,
                        ]);
                    }
                } else {
                    echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

                    return $this->redirect(['site/index']);
                }
            }

            /**
            * Updates an existing Vendoritem model.
            * If update is successful, the browser will be redirected to the 'view' page.
            *
            * @param string $id
            *
            * @return mixed
            */
            public function actionUpdate($id, $vid = false)
            {
                $access = Authitem::AuthitemCheck('2', '23');
                if (yii::$app->user->can($access)) {
                    $model = $this->findModel($id);
                    $model1 = new Image();
                    $model_question = Vendoritemquestion::find()
                    ->where(['item_id' => $id, 'answer_id' => null, 'question_answer_type' => 'selection'])
                    ->orwhere(['item_id' => $id, 'question_answer_type' => 'text', 'answer_id' => null])
                    ->orwhere(['item_id' => $id, 'question_answer_type' => 'image', 'answer_id' => null])
                    ->asArray()->all();

                    /* BEGIN gallery */
                    $base = Yii::$app->basePath;
                    $len = rand(1, 1000);
                    $item_id = $model->item_id;
                    // Item image path values
                    $imagedata = Image::find()->where('item_id = :id AND module_type = :status', [':id' => $id, ':status' => 'vendor_item'])->orderby(['vendorimage_sort_order' => SORT_ASC])->all();
                    // Item image path SALES and  RENTAL values
                    $guideimagedata = Image::find()->where('item_id = :id AND module_type = :status', [':id' => $id, ':status' => 'guides'])->orderBy(['vendorimage_sort_order' => SORT_ASC])->all();
                    /* END gallery */

                    $cat_id = $model->category_id;
                    $subcat_id = $model->subcategory_id;
                    $itemtype = Itemtype::loaditemtype();
                    $vendorname = Vendor::loadvendorname();
                    $categoryname = Category::vendorcategory(Yii::$app->user->getId());
                    $subcategory = Subcategory::loadsubcategory($cat_id);
                    $childcategory = Childcategory::loadchildcategory($subcat_id);
                    $loadpricevalues = Vendoritempricing::loadpricevalues($item_id);

                    // BEGIN themes and groups
                    $themelist = Themes::loadthemename();
                    $selected_themes = Vendoritemthemes::find()->where('item_id = "'.$id.'"')->one();

                    $theme_selected = Themes::loadthemenameupdate($selected_themes['theme_id']);
                    $exist_themes = explode(',', $selected_themes['theme_id']);
                    $model->themes = $exist_themes;
                    $selected_groups = Featuregroupitem::find()->where('item_id = "'.$id.'"')->one();
                    $exist_groups = explode(',', $selected_groups['group_id']);
                    $model->groups = $exist_groups;
                    // END themes and groups

                    $grouplist = Featuregroup::loadfeaturegroup();
                    // Values for priority log table dont delete...
                    $vendorid = $model->vendor_id;
                    $itemid = $model->item_id;
                    $priorityvalue = $model->priority;

                    if ($model->load(Yii::$app->request->post())) {
                        $model->slug = Yii::$app->request->post()['Vendoritem']['item_name'];
                        $c_slug1 = strtolower($model->slug);
                        $c_slug2 = str_replace(' ', '-', $c_slug1);
                        //Make alphanumeric (removes all other characters)
                        $c_slug3 = preg_replace("/[^a-z0-9_\s-]/", '', $c_slug2);
                        //Convert whitespaces and underscore to dash
                        $c_slug4 = preg_replace("/[\s_]/", '-', $c_slug3);
                        $c_slug4 = preg_replace("/[\s_]/", '-', $c_slug3);
                        // $c_slug5 = addslashes($c_slug4);
                        $model->slug = $c_slug4;

                        $chk_item_exist = Yii::$app->db->createCommand('SELECT *  FROM {{%vendor_item}} WHERE trash="Default" and `slug` LIKE "'.$c_slug4.'"')->queryAll();
                        if (!empty($chk_item_exist)) {
                            $tbl_vendor = Yii::$app->db->createCommand('Select vendor_name from {{%vendor}} where vendor_id ='.$model->vendor_id)->queryOne();
                            $vendorname = str_replace(' ', '-', $tbl_vendor['vendor_name']);
                            $model->slug = $c_slug4.'-'.$vendorname;
                        }
                        $model->item_for_sale = (Yii::$app->request->post()['Vendoritem']['item_for_sale']) ? 'Yes' : 'No';
                        $model->item_status = (Yii::$app->request->post()['Vendoritem']['item_status'] == 1) ? 'Active' : 'Deactive';
                        if ($model->save()) {

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

                    $guide_tbl = Yii::$app->db->createCommand()->insert('whitebook_image', [
                        'image_path' => $filename,
                        'item_id' => $itemid,
                        'image_user_id' => Yii::$app->user->getId(),
                        'module_type' => 'guides',
                        'vendorimage_sort_order' => $i, ])
                        ->execute();
                        ++$i;
                        }
                   }
                }

               /* Begin Upload guide image table  */
                $product_file = UploadedFile::getInstances($model, 'image_path');
                if($product_file){
                    $i = count($imagedata) + 1;
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
                    }
        
                    $image_tbl = Yii::$app->db->createCommand()
                    ->insert('whitebook_image', [
                    'image_path' => $filename,'item_id' => $id,
                    'image_user_id' => Yii::$app->user->getId(),'module_type' => 'vendor_item',
                    'image_user_type' => 'admin','vendorimage_sort_order' => $i, ])
                    ->execute();++$i;
                   }
                }

                /* Delete item price table records if its available any price for item type rental or service */
                if ($model->type_id == 2) {
                    Vendoritempricing::deleteAll('item_id = :item_id', [':item_id' => $model->item_id]);
                }
                                

                                if ($model->priority != $priorityvalue) {
                                        $query = (new \yii\db\Query())
                                        ->select('log_id')
                                        ->from('whitebook_priority_log')
                                        ->where(['vendor_id' => $vendorid, 'item_id' => $itemid])
                                        ->orderBy(['log_id' => SORT_DESC])
                                        ->limit(1)
                                        ->all();
                                        if ($query) {
                                            $sql = 'UPDATE whitebook_priority_log SET `priority_end_date`="'.$model->created_datetime.'" WHERE `log_id`='.$query[0]['log_id'];
                                            $command = \Yii::$app->db->createCommand($sql);
                                            $command->execute();
                                        }
                                        $command = Yii::$app->db->createCommand()
                                        ->insert('whitebook_priority_log', [
                                            'vendor_id' => $vendorid,
                                            'item_id' => $itemid,
                                            'priority_level' => $model->priority,
                                            'priority_start_date' => $model->created_datetime, ])
                                            ->execute();
                                        }
                                        $itemid = $model->item_id;
                                        $save = 'update';

                                        //BEGIN Manage item pricing table
                                        if (isset($_POST['vendoritem-item_price']['from']) && $_POST['vendoritem-item_price']['from'] != '') {
                                            Vendoritempricing::deleteAll('item_id = :item_id', [':item_id' => $item_id]);
                                            $from = $_POST['vendoritem-item_price']['from'];
                                            $to = $_POST['vendoritem-item_price']['to'];
                                            $price = $_POST['vendoritem-item_price']['price'];
                                            for ($opt = 0;$opt < count($from);++$opt) {
                                                $vendor_item_pricing = new Vendoritempricing();
                                                $vendor_item_pricing->item_id = $itemid;
                                                $vendor_item_pricing->range_from = $from[$opt];
                                                $vendor_item_pricing->range_to = $to[$opt];
                                                $vendor_item_pricing->pricing_price_per_unit = $price[$opt];
                                                $vendor_item_pricing->save();
                                            }
                                        }
                                        //END Manage item pricing table

                                        /* Themes table Begin*/

                                        if (isset($_POST['Vendoritem']['themes']) && $_POST['Vendoritem']['themes'] != '') {
                                            $save = 'update';
                                            if (!isset($selected_themes)) {
                                                $selected_themes = new Vendoritemthemes();
                                                $selected_themes->vendor_id = $model['vendor_id'];
                                                $save = 'save';
                                            }
                                            $theme_id = implode(',', $_POST['Vendoritem']['themes']);
                                            $selected_themes->item_id = $itemid;
                                            $selected_themes->theme_id = $theme_id;
                                            $selected_themes->$save();
                                        }
                                        /* Themes table End */

                                        /* Groups table Begin*/
                                        if (isset($_POST['Vendoritem']['groups']) && $_POST['Vendoritem']['groups'] != '') {
                                            $save = 'update';
                                            if (!isset($selected_groups)) {
                                                $selected_groups = new Featuregroupitem();
                                                $selected_groups->vendor_id = $model['vendor_id'];
                                                $save = 'save';
                                            }
                                            $group_id = implode(',', $_POST['Vendoritem']['groups']);
                                            $selected_groups->item_id = $itemid;
                                            $selected_groups->group_id = $group_id;
                                            $selected_groups->$save();
                                        }
                                        /* Groups table End */

                                        if (isset($_POST['Vendoritemquestion']) && $_POST['Vendoritemquestion'] != '') {
                                            foreach ($_POST['Vendoritemquestion'] as $questons) {
                                                if ((isset($questons['question_text'][0]) && isset($questons['question_answer_type'][0])) && ($questons['question_text'][0] && $questons['question_answer_type'][0])) {
                                                    if (isset($questons['update'][0])) {
                                                        $model_question = Vendoritemquestion::findOne($questons['update'][0]);
                                                        $model_question->item_id = $itemid;
                                                        $model_question->question_text = $questons['question_text'][0];
                                                        $model_question->question_answer_type = $questons['question_answer_type'][0];
                                                        $model_question->selection_option = '';
                                                        $model_question->selection_price = '';

                                                        if ($model_question->question_answer_type == 'selection') {
                                                            $model_question->selection_option = serialize($questons['text']);
                                                            $model_question->selection_price = serialize($questons['price']);
                                                        } else {
                                                            $model_question->price = $questons['price'][0];
                                                        }
                                                        $model_question->update();
                                                    } else {
                                                        $model_question = new Vendoritemquestion();
                                                        $model_question->item_id = $itemid;
                                                        $model_question->question_text = $questons['question_text'][0];
                                                        $model_question->question_answer_type = $questons['question_answer_type'][0];
                                                        $model_question->selection_option = '';
                                                        $model_question->selection_price = '';

                                                        if ($model_question->question_answer_type == 'selection') {
                                                            $model_question->selection_option = serialize($questons['text']);
                                                            $model_question->selection_price = serialize($questons['price']);
                                                        } else {
                                                            $model_question->price = $questons['price'][0];
                                                        }
                                                        $model_question->save();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    echo Yii::$app->session->setFlash('success', 'Vendor item updated successfully!');
                                    Yii::info('[Item Updated] Admin updated '.addslashes($model->item_name).' item information', __METHOD__);
                                    if (isset($_GET['vid']) != '') {
                                        return $this->redirect(['vendor/view?id='.$_GET['vid']]);
                                    } else {
                                        return $this->redirect(['index']);
                                    }
                                } else {
                                    return $this->render('update', [
                                        'model' => $model, 'itemtype' => $itemtype, 'vendorname' => $vendorname, 'subcategory' => $subcategory, 'categoryname' => $categoryname,
                                        'imagedata' => $imagedata, 'model_question' => $model_question, 'themelist' => $themelist, 'grouplist' => $grouplist,
                                        'exist_themes' => $exist_themes, 'childcategory' => $childcategory, 'loadpricevalues' => $loadpricevalues, 'guideimagedata' => $guideimagedata,
                                    ]);
                                }
                            } else {
                                echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

                                return $this->redirect(['site/index']);
                            }
                        }

                        /**
                        * Deletes an existing Vendoritem model.
                        * If deletion is successful, the browser will be redirected to the 'index' page.
                        *
                        * @param string $id
                        *
                        * @return mixed
                        */
                        public function actionDelete($id)
                        {
                            $access = Authitem::AuthitemCheck('3', '23');
                            if (yii::$app->user->can($access)) {                               
                                $tab1 = \Yii::$app->db->createCommand('DELETE FROM  whitebook_priority_item WHERE item_id = '.$id);
                                $tab1->execute();
                                $tab2 = \Yii::$app->db->createCommand('DELETE FROM whitebook_feature_group_item WHERE item_id = '.$id);
                                $tab2->execute();
                                $tab3 = \Yii::$app->db->createCommand('DELETE FROM whitebook_image WHERE item_id = '.$id);
                                $tab3->execute();
                                $tab4 = \Yii::$app->db->createCommand('UPDATE whitebook_vendor_item SET trash="Deleted" WHERE item_id = '.$id);
                                $tab4->execute();
                                echo Yii::$app->session->setFlash('success', 'Vendor item deleted successfully!');
                                return $this->redirect(['index']);
                            } else {
                                echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
                                return $this->redirect(['site/index']);
                            }
                        }

                        public function actionCheck($image_id)
                        {
                            $user = Image::findOne($image_id);
                            $user->delete();
                        }

                        /**
                        * Finds the Vendoritem model based on its primary key value.
                        * If the model is not found, a 404 HTTP exception will be thrown.
                        *
                        * @param string $id
                        *
                        * @return Vendoritem the loaded model
                        *
                        * @throws NotFoundHttpException if the model cannot be found
                        */
                        protected function findModel($id)
                        {
                            if (($model = Vendoritem::findOne($id)) !== null) {
                                return $model;
                            } else {
                                throw new NotFoundHttpException('The requested page does not exist.');
                            }
                        }

                        public function actionBlock()
                        {
                            if (Yii::$app->request->isAjax) {
                                $data = Yii::$app->request->post();
                            }
                            $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
                            $command = \Yii::$app->db->createCommand('UPDATE whitebook_vendor_item SET item_status="'.$status.'" WHERE item_id='.$data['id']);
                            $command->execute();
                            if ($status == 'Active') {
                                return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
                            } else {
                                return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
                            }
                        }

                        /* Vendor item gridview status changes */
                        public function actionStatus()
                        {
                            if (Yii::$app->request->isAjax) {
                                $data = Yii::$app->request->post();
                            }
                            $ids = implode('","', $data['keylist']);
                            if ($data['status'] == 'Delete') {
                                $command = \Yii::$app->db->createCommand('DELETE FROM whitebook_vendor_item  WHERE item_id IN("'.$ids.'")');
                                $command->execute();
                                if ($command) {
                                    echo Yii::$app->session->setFlash('success', 'Vendor item deleted successfully!');
                                } else {
                                    echo Yii::$app->session->setFlash('danger', 'Something went wrong');
                                }
                            } elseif ($data['status'] == 'Reject') {
                                $command = \Yii::$app->db->createCommand('UPDATE whitebook_vendor_item SET item_approved="rejected" WHERE item_id IN("'.$ids.'")');
                                $command->execute();
                                if ($command) {
                                    echo Yii::$app->session->setFlash('success', 'Vendor item rejected successfully!');
                                } else {
                                    echo Yii::$app->session->setFlash('danger', 'Something went wrong');
                                }
                            } else {
                                $command = \Yii::$app->db->createCommand('UPDATE whitebook_vendor_item SET item_status="'.$data['status'].'" WHERE item_id IN("'.$ids.'")');
                                $command->execute();
                                if ($command) {
                                    echo Yii::$app->session->setFlash('success', 'Vendor item status updated!');
                                } else {
                                    echo Yii::$app->session->setFlash('danger', 'Something went wrong');
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
                                $command = \Yii::$app->db->createCommand('DELETE FROM whitebook_vendor_item_question  WHERE question_id ="'.$data['question_id'].'"');
                                $command->execute();
                                if ($command) {
                                    echo 'Question and answers deleted successfully';
                                }
                            }

                            public function actionSort_vendor_item()
                            {
                                $sort = $_POST['sort_val'];
                                $item_id = $_POST['item_id'];
                                $command = \Yii::$app->DB->createCommand(
                                'UPDATE whitebook_vendor_item SET sort="'.$sort.'" WHERE item_id='.$item_id);

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

                                        // check if exist question
                                        if ($data['serial_div'][0]['value'] != '') {
                                            $exist = Vendoritemquestion::findOne($data['serial_div'][0]['value']);
                                            if (!empty($exist)) {
                                                $sql = 'UPDATE whitebook_vendor_item_question SET `question_text`="'.$data['serial_div'][1]['value'].'" WHERE `question_id`='.$data['serial_div'][0]['value'];
                                                $command = \Yii::$app->db->createCommand($sql);
                                                $command->execute();
                                                $q_id[] = $data['serial_div'][0]['value'];
                                            } else {
                                                $model_question = new Vendoritemquestion();
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
                                                    $model_answer_option = new Vendoritemquestionansweroption();
                                                    if (isset($data['serial_div'][3]['value']) && $data['serial_div'][3]['value'] != '') {
                                                        $model_answer_option->answer_price_added = $data['serial_div'][3]['value'];
                                                    } else {
                                                        $model_answer_option->answer_price_added = 0;
                                                    }
                                                } else {
                                                    $ques_id = $data['serial_div'][3]['value'];
                                                    $model_answer_option = Vendoritemquestionansweroption::findOne($ques_id);
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
                                                        $model_answer_option = Vendoritemquestionansweroption::findOne($ques_id);
                                                    } else {
                                                        $model_answer_option = new Vendoritemquestionansweroption();
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
                            public function actionGuideimage()
                            {
                                $base = Yii::$app->basePath;
                                $len = rand(1, 1000);
                                $path = $base.'/web/uploads/vendor_images/';//server path

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
                                    $image_tbl = Yii::$app->db->createCommand()->insert('whitebook_image', [
                                        'image_path' => $name,
                                        'item_id' => $_POST['item_id'],
                                        'image_user_id' => Yii::$app->user->getId(),
                                        'module_type' => 'guides',
                                        'image_user_type' => 'admin',
                                        'vendorimage_sort_order' => 0, ])
                                        ->execute();
                                        $last_id = Yii::$app->db->getLastInsertID();

                                        // guide image table
                                        $image_tbl = Yii::$app->db->createCommand()->insert('whitebook_vendor_item_question_guide', [
                                            'question_id' => $_POST['question_id'],
                                            'guide_image_id' => $last_id, ])->execute();
                                            echo $path.$name;
                                        }
                                    }

                                    public function actionRenderquestion()
                                    {
                                        if (Yii::$app->request->isAjax) {
                                            $data = Yii::$app->request->post();
                                            $question = Vendoritemquestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

                                            if ($question[0]['question_answer_type'] == 'image') {
                                                $answers = Vendoritemquestionguide::find()->where(['question_id' => $data['q_id']])->asArray()->all();
                                            } else {
                                                $answers = Vendoritemquestionansweroption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
                                            }

                                            return $this->renderPartial('questionanswer', ['question' => $question, 'answers' => $answers]);
                                        }
                                    }

                                    public function actionViewrenderquestion()
                                    {
                                        if (Yii::$app->request->isAjax) {
                                            $data = Yii::$app->request->post();
                                            $question = Vendoritemquestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

                                            if ($question[0]['question_answer_type'] == 'image') {
                                                $answers = Vendoritemquestionguide::find()->where(['question_id' => $data['q_id']])->asArray()->all();
                                            } else {
                                                $answers = Vendoritemquestionansweroption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
                                            }

                                            return $this->renderPartial('viewquestionanswer', ['question' => $question, 'answers' => $answers]);
                                            die; /* ALL DIE STATEMENT IMPORTANT FOR VENDOR PANEL*/
                                        }
                                    }

                                    public function actionRenderanswer()
                                    {
                                        if (Yii::$app->request->isAjax) {
                                            $data = Yii::$app->request->post();
                                            $question = Vendoritemquestion::find()->where('answer_id = "'.$data['q_id'].'"')->asArray()->all();
                                            $answers = Vendoritemquestionansweroption::find()->where(['question_id' => $question[0]['question_id']])->asArray()->all();

                                            return $this->renderPartial('questionanswer', ['question' => $question, 'answers' => $answers]);
                                        }
                                    }

                                    public function actionGalleryupload($id)
                                    {
                                        $base = Yii::$app->basePath;
                                        $len = rand(1, 1000);
                                        $model = new Image();
                                        $imagedata = Image::find()->where('item_id = :id AND module_type = :status', [':id' => $id, ':status' => 'vendor_item'])->orderby(['vendorimage_sort_order' => SORT_ASC])->all();

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
                                                    $k = Yii::$app->db->createCommand()
                                                    ->insert('whitebook_image', [
                                                        'image_path' => $model->image_path,
                                                        'item_id' => $id,
                                                        'image_user_id' => $model->image_user_id,
                                                        'module_type' => 'vendor_item',
                                                        'vendorimage_sort_order' => $i, ])
                                                        ->execute();
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
                                        public function actionSalesguideimage($id = '')
                                        {
                                            $base = Yii::$app->basePath;
                                            $len = rand(1, 1000);
                                            $model = new Vendoritem();
                                            $model1 = new Image();
                                            if (Yii::$app->request->isAjax) {
                                                $data = Yii::$app->request->post();
                                                if (isset($data['question_id']) &&  $data['question_id'] != '') {
                                                    $guideimageval = Vendoritemquestionguide::find()->select('guide_image_id')->where('question_id = :id', [':id' => $data['question_id']])->all();
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

                                                        $image_tbl = Yii::$app->db->createCommand()->insert('whitebook_image', [
                                                            'image_path' => $model1->image_path,
                                                            'item_id' => '001',
                                                            'image_user_id' => $model1->image_user_id,
                                                            'module_type' => 'sales_guides',
                                                            'vendorimage_sort_order' => 0, ])
                                                            ->execute();
                                                            $last_id = Yii::$app->db->getLastInsertID();
                                                            $guide = Yii::$app->db->createCommand()->insert('whitebook_vendor_item_question_guide', [
                                                                'question_id' => $id,
                                                                'guide_image_id' => $last_id,
                                                            ])
                                                            ->execute();
                                                            die;
                                                        }
                                                    }

                                                    return $this->renderPartial('salesguide', ['model' => $model, 'guideimagedata' => (isset($guideimagedata) && is_array($guideimagedata)) ? $guideimagedata : array(), 'question_id' => $data['question_id']]);
                                                }
                                            }

                                            // Delete item type sales image
                                            public function actionDeletesalesimage()
                                            {
                                                $model1 = new Image();
                                                if (Yii::$app->request->isAjax) {
                                                    $data = Yii::$app->request->post();
                                                    if (isset($data['key']) &&  $data['key'] != '') {
                                                        $image_path = Image::loadimageids($data['key']);
                                                        unlink(Yii::getAlias('@sales_guide_images').$image_path[0]['image_path']);
                                                        $image = \Yii::$app->db->createCommand('DELETE FROM whitebook_image WHERE image_id='.$data['key'])->execute();
                                                        $guide_image = \Yii::$app->db->createCommand('DELETE FROM whitebook_vendor_item_question_guide WHERE guide_image_id='.$data['key'])->execute();
                                                    }
                                                }
                                            }
                                            // Delete item image
                                            public function actionDeleteitemimage()
                                            {
                                                $model1 = new Image();
                                                if (Yii::$app->request->isAjax) {
                                                    $data = Yii::$app->request->post();
                                                    if (isset($data['key']) &&  $data['key'] != '') {
                                                        $image_path = Image::loadguideimageids($data['key']);
                                                        Vendoritem::deleteFiles($image_path);
                                                        $image = \Yii::$app->db->createCommand('DELETE FROM whitebook_image WHERE image_id=:image_id');
                                                        $image->bindParam(':image_id', $image_id);
                                                        $image_id = $data['key'];
                                                        $image->execute();
                                                        die; // dont remove die, action used by vendor module also.
                                                    }
                                                }
                                            }

                                            // Delete item type service or rental image
                                            public function actionDeleteserviceguideimage()
                                            {
                                                $model1 = new Image();
                                                if (Yii::$app->request->isAjax) {
                                                    $data = Yii::$app->request->post();
                                                    if (isset($data['key']) &&  $data['key'] != '') {
                                                        $image_path = Image::loadserviceguideimageids($data['key']);
                                                        unlink(Yii::getAlias('@sales_guide_images').$image_path[0]['image_path']);
                                                        $image = \Yii::$app->db->createCommand('DELETE FROM whitebook_image WHERE image_id='.$data['key'])->execute();
                                                    }
                                                }
                                            }

                                            public function actionItemnamecheck()
                                            {
                                                if (Yii::$app->request->isAjax) {
                                                    $data = Yii::$app->request->post();
                                                }
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
                                                        echo  $result = 0;
                                                        die;
                                                    } else {
                                                        echo  $result = 1;
                                                        die;
                                                    }
                                                }
                                                echo $result = count($itemname);
                                                die;
                                            }
                                        }
