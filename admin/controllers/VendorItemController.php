<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use yii\helpers\UploadHandler;
use yii\helpers\Html;
use yii\helpers\Url;
use admin\models\VendorItem;
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
use common\models\EventItemlink;
use common\models\VendorDraftItem;

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
            ->where(new \yii\db\Expression('FIND_IN_SET(:item_id, item_id)'))
            ->addParams([':item_id' => $id])
            ->all();

        $model_question = VendorItemQuestion::find()
            ->where(['item_id' => $id, 'answer_id' => null, 'question_answer_type' => 'selection'])
            ->orwhere(['item_id' => $id, 'question_answer_type' => 'text', 'answer_id' => null])
            ->orwhere(['item_id' => $id, 'question_answer_type' => 'image', 'answer_id' => null])
            ->asArray()
            ->all();

        $imagedata = Image::find()
            ->where('item_id = :id', [':id' => $id])
            ->orderby(['vendorimage_sort_order' => SORT_ASC])
            ->all();

        $categories = VendorItemToCategory::find()
            ->with('category')
            ->Where(['item_id' => $id])
            ->all();

        return $this->render('view', [
            'model' => $this->findModel($id), 
            'dataProvider1' => $dataProvider1, 
            'model_question' => $model_question, 
            'imagedata' => $imagedata,
            'categories' => $categories
        ]);
    }

    /**
    * Creates a new VendorItem model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @return mixed
    */
    public function actionCreate($vid = '')
    {
        $model = new VendorItem();
        $model_question = new VendorItemQuestion();
        $model1 = new Image();
        $themelist = Themes::loadthemename();

        $grouplist = FeatureGroup::loadfeaturegroup();
        $base = Yii::$app->basePath;
        $len = rand(1, 1000);
        $itemtype = ItemType::loaditemtype();
        $vendorname = Vendor::loadvendorname();

        if ($model->load(Yii::$app->request->post())) {

            $model->item_for_sale = (Yii::$app->request->post()['VendorItem']['item_for_sale']) ? 'Yes' : 'No';

            $max_sort = $model->findBysql("SELECT MAX(`sort`) as sort FROM `whitebook_vendor_item` where trash = 'Default'")->asArray()->all();

            $sort = ($max_sort[0]['sort'] + 1);

            $model->sort = $sort;
            $model->slug = Yii::$app->request->post()['VendorItem']['item_name'];
            $c_slug1 = strtolower($model->slug);
            $c_slug2 = str_replace(' ', '-', $c_slug1);
            //Make alphanumeric (removes all other characters)
            $c_slug3 = preg_replace("/[^a-z0-9_\s-]/", '', $c_slug2);
            //Convert whitespaces and underscore to dash
            $c_slug4 = preg_replace("/[\s_]/", '-', $c_slug3);
            $model->slug = $c_slug4;

            $chk_item_exist = VendorItem::find()
                ->where(['trash'=>'default'])
                ->andWhere(['LIKE','slug',$c_slug4])
                ->one();

            if (!empty($chk_item_exist)) {

                $vendor_item = Yii::$app->request->post('VendorItem');

                $tbl_vendor = Vendor::find()
                    ->select('vendor_name')
                    ->where(['vendor_id' => $vendor_item['vendor_id']])
                    ->one();

                $vendorname = str_replace(' ', '-', $tbl_vendor['vendor_name']);
                $model->slug = $c_slug4.'-'.$vendorname;
            }

            if ($model->save()) {

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

                //BEGIN Manage item pricing table
                $vendoritem_item_price = Yii::$app->request->post('vendoritem-item_price');

                if ($vendoritem_item_price) {

                    $from = $vendoritem_item_price['from'];
                    $to = $vendoritem_item_price['to'];
                    $price = $vendoritem_item_price['price'];

                    for ($opt = 0;$opt < count($from);++$opt) {
                        $vendor_item_pricing = new VendorItemPricing();
                        $vendor_item_pricing->item_id = $itemid;
                        $vendor_item_pricing->range_from = $from[$opt];
                        $vendor_item_pricing->range_to = $to[$opt];
                        $vendor_item_pricing->pricing_price_per_unit = $price[$opt];
                        $vendor_item_pricing->save();
                    }
                }
                //END Manage item pricing table

                /* Themes table Begin*/

                $vendor_item = Yii::$app->request->post('VendorItem');

                if (isset($vendor_item['themes']) && $_POST['VendorItem']['themes'] != '' && count($vendor_item['themes'])>0 ) {
                    foreach($vendor_item['themes'] as $value) {
                        $themeModel = new VendorItemThemes();
                        $themeModel->item_id = $itemid;
                        $themeModel->theme_id = $value;
                        $themeModel->save();
                    }
                }
                /* Themes table End */

                /* Groups table Begin*/

                if (isset($vendor_item['groups']) && $_POST['VendorItem']['groups'] != '' && count($vendor_item['groups'])>0 ) {
                    foreach ($vendor_item['groups'] as $value) {
                        $groupModel = new FeatureGroupItem();
                        $groupModel->item_id = $itemid;
                        $groupModel->group_id = $value;
                        $groupModel->vendor_id = $vendor_item['vendor_id'];
                        $groupModel->save();
                    }
                }

                //add new images
                $images = Yii::$app->request->post('images');

                if(!$images) {
                    $images = [];
                }

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
                
                Yii::$app->session->setFlash('success', 'Vendor item added successfully!');
                Yii::info('[New Item Created by '. Yii::$app->user->identity->admin_name .'] New Item added: '.addslashes($model->item_name), __METHOD__);

                return $this->redirect(['index']);

            }//if model->savel()

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
                'model_question' => $model_question,
                'themelist' => $themelist,
                'grouplist' => $grouplist,
                'categories' => $categories
            ]);
        }
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
        $model = $this->findModel($id);

        $model->themes = \yii\helpers\ArrayHelper::map($model->vendorItemThemes, 'theme_id', 'theme_id');
        $model->groups = \yii\helpers\ArrayHelper::map($model->featureGroupItems, 'group_id', 'group_id');

        $model_question = VendorItemQuestion::find()
            ->where(['item_id' => $id, 'answer_id' => null, 'question_answer_type' => 'selection'])
            ->orwhere(['item_id' => $id, 'question_answer_type' => 'text', 'answer_id' => null])
            ->orwhere(['item_id' => $id, 'question_answer_type' => 'image', 'answer_id' => null])
            ->asArray()->all();

        $item_id = $model->item_id;

        $categoryname = Category::vendorcategory($model->vendor_id);

        $grouplist = FeatureGroup::loadfeaturegroup();

        // Values for priority log table dont delete...
        $vendorid = $model->vendor_id;
        $itemid = $model->item_id;
        $priorityvalue = $model->priority;

        if ($model->load(Yii::$app->request->post())) {

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

            $model->slug = Yii::$app->request->post()['VendorItem']['item_name'];

            $c_slug1 = strtolower($model->slug);
            $c_slug2 = str_replace(' ', '-', $c_slug1);
            $c_slug3 = preg_replace("/[^a-z0-9_\s-]/", '', $c_slug2); //Make alphanumeric (removes all other characters)
            $c_slug4 = preg_replace("/[\s_]/", '-', $c_slug3);
            $model->slug = $c_slug4;

            $chk_item_exist = VendorItem::find()
                ->where(['trash' => 'default'])
                ->andWhere(['LIKE', 'slug', $c_slug4])
                ->one();

            if (!empty($chk_item_exist)) {

                $vendoritem = Yii::$app->request->post('VendorItem');

                $tbl_vendor = Vendor::find()
                    ->select('vendor_name')
                    ->where(['vendor_id' => $vendoritem['vendor_id']])
                    ->one();

                $vendorname = str_replace(' ', '-', $tbl_vendor['vendor_name']);
                $model->slug = $c_slug4 . '-' . $vendorname;
            }

            $model->item_for_sale = (Yii::$app->request->post()['VendorItem']['item_for_sale']) ? 'Yes' : 'No';
            $model->item_status = (Yii::$app->request->post()['VendorItem']['item_status'] == 1) ? 'Active' : 'Deactive';

            if ($model->save()) {
               
                //remove old images
                Image::deleteAll(['item_id' => $id]);

                //add new images
                $images = Yii::$app->request->post('images');

                if(!$images) {
                    $images = [];
                }

                foreach ($images as $key => $value) {
                    $image = new Image();
                    $image->image_path = $value['image_path'];
                    $image->item_id = $id;
                    $image->image_user_id = Yii::$app->user->getId();
                    $image->module_type = 'vendor_item';
                    $image->image_user_type = 'admin';
                    $image->vendorimage_sort_order = $value['vendorimage_sort_order'];
                    $image->save();
                }

                if ($model->priority != $priorityvalue) {

                    $query = Prioritylog::find()
                        ->select('log_id')
                        ->where(['vendor_id' => $vendorid, 'item_id' => $itemid])
                        ->orderBy(['log_id' => SORT_DESC])
                        ->limit(1)
                        ->all();

                    if ($query) {
                        $prioritylog = Prioritylog::findOne($query[0]['log_id']);
                        $prioritylog->priority_end_date = $model->created_datetime;
                        $prioritylog->update();
                    }

                    $prioritylog = new Prioritylog;
                    $prioritylog->vendor_id = $vendorid;
                    $prioritylog->item_id = $itemid;
                    $prioritylog->priority_level = $model->priority;
                    $prioritylog->priority_start_date = $model->created_datetime;
                    $prioritylog->save();
                }

                $itemid = $model->item_id;
                $save = 'update';

                //BEGIN Manage item pricing table
                VendorItemPricing::deleteAll('item_id = :item_id', [':item_id' => $item_id]);

                $vendoritem_item_price = Yii::$app->request->post('vendoritem-item_price');

                if ($vendoritem_item_price) {

                    $from = $vendoritem_item_price['from'];
                    $to = $vendoritem_item_price['to'];
                    $price = $vendoritem_item_price['price'];

                    for ($opt = 0; $opt < count($from); ++$opt) {
                        $vendor_item_pricing = new VendorItemPricing();
                        $vendor_item_pricing->item_id = $itemid;
                        $vendor_item_pricing->range_from = $from[$opt];
                        $vendor_item_pricing->range_to = $to[$opt];
                        $vendor_item_pricing->pricing_price_per_unit = $price[$opt];
                        $vendor_item_pricing->save();
                    }
                }
                //END Manage item pricing table

                /* Themes table Begin*/
                $vendor_item = Yii::$app->request->post('VendorItem');
                VendorItemThemes::deleteAll(['item_id' => $id]); # to clear old values
                if (isset($vendor_item['themes']) && count($vendor_item['themes']) > 0 && $_POST['VendorItem']['themes'] != '') {
                    foreach ($vendor_item['themes'] as $values) {
                        $themesModel = new VendorItemThemes();
                        $themesModel->item_id = $id;
                        $themesModel->theme_id = $values;
                        $themesModel->save();
                    }
                }

                if (isset($vendor_item['groups']) && $_POST['VendorItem']['groups'] != '' && count($vendor_item['groups']) > 0) {
                    FeatureGroupItem::deleteAll(['item_id' => $id]); # to clear old values
                    foreach ($vendor_item['groups'] as $value) {
                        $groupModel = new FeatureGroupItem();
                        $groupModel->item_id = $itemid;
                        $groupModel->group_id = $value;
                        $groupModel->vendor_id = $model->vendor_id;
                        $groupModel->save();
                    }
                }

                $vendor_item_question = Yii::$app->request->post('VendorItemQuestion');

                if ($vendor_item_question) {

                    foreach ($vendor_item_question as $questons) {
                        if ((isset($questons['question_text'][0]) && isset($questons['question_answer_type'][0])) && ($questons['question_text'][0] && $questons['question_answer_type'][0])) {
                            if (isset($questons['update'][0])) {
                                $model_question = VendorItemQuestion::findOne($questons['update'][0]);
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
                                $model_question = new VendorItemQuestion();
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

                Yii::$app->session->setFlash('success', 'Vendor item With ID ' . $id . ' updated successfully!');

                Yii::info('[Item Updated] Admin updated ' . addslashes($model->item_name) . ' item information', __METHOD__);

                if (Yii::$app->request->get('vid')) {
                    return $this->redirect(['vendor/view?id=' . Yii::$app->request->get('vid')]);
                } else {
                    return $this->redirect(['index']);
                }
            }//if model->savel
        }//if model-load 

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
            'itemType' => ItemType::findAll(['trash' => 'Default']),
            'categoryname' => $categoryname,
            'images' => Image::findAll(['item_id' => $id, 'module_type' => 'vendor_item']),
            'model_question' => $model_question,
            'themes' => Themes::findAll(['theme_status' => 'Active', 'trash' => 'Default']),
            'grouplist' => $grouplist,
            'itemPricing' => VendorItemPricing::findAll(['item_id' => $item_id]),
            'guideImages' => Image::findAll(['item_id' => $id, 'module_type' => 'guides']),
            'vendor_item_to_category' => $vendor_item_to_category,
            'categories' => $categories
        ]);         
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

        //if new item 
        if(!$item_id) {
            $model = new VendorItem();         
        } else {
            $model = VendorItem::find()
                ->where(['item_id' => $item_id])
                ->one();
        }

        $model->load(['VendorItem' => $posted_data]);
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
        
        $model = VendorItem::find()
            ->where(['item_id' => $item_id])
            ->one();
    
        //load posted data to model 
        $model->load(['VendorItem' => $posted_data]);

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
        
        $model = VendorItem::find()
            ->where(['item_id' => $item_id])
            ->one();
    
        //load posted data to model 
        $model->load(['VendorItem' => $posted_data]);

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
    * Save item images from update and create page
    *
    * @return json
    */
    public function actionItemApproval() 
    {
        $item_id = Yii::$app->request->post('item_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

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
        
        $model = VendorItem::find()
            ->where(['item_id' => $item_id])
            ->one();
    
        //load posted data to model 
        $model->load(['VendorItem' => $posted_data]);

        //save data without validation 
        $model->save(false);

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id
        ];
    }

    /**
    * Save item images from update and create page
    *
    * @return json
    */
    public function actionItemImages() 
    {
        $item_id = Yii::$app->request->post('item_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        $posted_data = VendorItem::get_posted_data();

        //validate
        if(!$is_autosave) {
            $errors = VendorItem::validate_item_images($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 
        
        $model = VendorItem::find()
            ->where(['item_id' => $item_id])
            ->one();
    
        //load posted data to model 
        $model->load(['VendorItem' => $posted_data]);

        //save data without validation 
        $model->save(false);

        //add new images
        $images = Yii::$app->request->post('images');

        if(!$images) {
            $images = array();
        }

        $arr_image_path = [];

        foreach ($images as $key => $value) {

            //check if image already added 
            $image = Image::find()
                ->where([
                    'item_id' => $item_id,
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
                $image->item_id = $item_id;
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
            Image::deleteAll('item_id=' . $item_id . ' AND 
                image_path NOT IN ("'.implode('","', $arr_image_path).'")');
        }
        
        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $model->item_id
        ];
    }

    /**
    * Save item themes & groups from update and create page
    *
    * @return json
    */
    public function actionItemThemesGroups() 
    {
        $item_id = Yii::$app->request->post('item_id'); 
        $vendor_item = Yii::$app->request->post('VendorItem');

        if (empty($vendor_item['themes'])){
            $vendor_item['themes'] = [];
        }

        $arr_theme = [];

        foreach ($vendor_item['themes'] as $value) {

            $themesModel = VendorItemThemes::find()
                ->where([
                    'item_id' => $item_id,
                    'theme_id' => $value
                ])
                ->one();

            if(!$themesModel) {
                $themesModel = new VendorItemThemes();
                $themesModel->item_id = $item_id;
                $themesModel->theme_id = $value;
                $themesModel->save();
            }            

            $arr_theme[] = $value;
        }

        if($arr_theme) {
            VendorItemThemes::deleteAll('item_id = ' . $item_id . ' AND 
                theme_id NOT IN ('.implode(',', $arr_theme).')');    
        }
        
        if (empty($vendor_item['groups'])){
            $vendor_item['groups'] = [];
        }

        $arr_group = [];

        foreach ($vendor_item['groups'] as $value) {

            $groupModel = FeatureGroupItem::find()
                ->where([
                    'item_id' => $item_id,
                    'group_id' => $value
                ])
                ->one();


            if(!$groupModel) {

                $model = VendorItem::findOne($item_id);
                
                $groupModel = new FeatureGroupItem();
                $groupModel->item_id = $item_id;
                $groupModel->group_id = $value;
                $groupModel->vendor_id = $model->vendor_id;
                $groupModel->save();  
            }            

            $arr_group[] = $value;
        }

        if($arr_group) {
            FeatureGroupItem::deleteAll('item_id = ' . $item_id . ' AND 
                group_id NOT IN ('.implode(',', $arr_group).')');     
        }        
        
        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'item_id' => $item_id
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
        
        $errors = VendorItem::validate_form($posted_data);

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
    *
    * @param string $id
    *
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

    public function actionBlock()
    {
        if (!Yii::$app->request->isAjax) {
            die();
        }

        $data = Yii::$app->request->post();
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        $vendor_item_update = VendorItem::findOne($data['id']);
        $vendor_item_update->item_status = $status;
        $vendor_item_update->update();
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
