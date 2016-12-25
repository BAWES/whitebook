<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use admin\models\Vendor;
use admin\models\AuthItem;
use admin\models\Category;
use admin\models\VendorSearch;
use common\models\Prioritylog;
use common\models\PrioritylogSearch;
use common\models\Siteinfo;
use common\models\VendorItemCapacityException;
use common\models\VendorItemCapacityExceptionSearch;
use common\models\VendorItemSearch;
use common\models\VendorOrderAlertEmails;
use common\models\Suborder;
use common\models\Image;
use common\models\Vendoritempricing;
use common\models\VendorItemThemes;
use common\models\VendorItemToCategory;
use common\models\CustomerCart;
use common\models\Priorityitem;
use common\models\EventItemlink;
use common\models\Featuregroupitem;
use common\models\VendorLocation;
use common\models\VendorItem;
use common\models\VendorCategory;
use common\models\BlockedDate;
use common\models\DeliveryTimeSlot;
use common\models\VendorPhoneNo;

/**
 * VendorController implements the CRUD actions for Vendor model.
 */
class VendorController extends Controller
{
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
     * Lists all Vendor models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;

        $searchModel = new VendorItemSearch();
        $dataProvider = $searchModel->searchviewVendor(Yii::$app->request->queryParams, $request->get('id'));

        $searchModel3 = new VendorItemCapacityExceptionSearch();
        $dataProvider3 = $searchModel3->search(Yii::$app->request->queryParams, $id);

        $vendor_order_alert_emails = VendorOrderAlertEmails::find()
            ->where(['vendor_id' => $id])
            ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'searchModel3' => $searchModel3,
            'dataProvider' => $dataProvider,
            'dataProvider3' => $dataProvider3,
            'vendor_order_alert_emails' => $vendor_order_alert_emails
        ]);
    }

    public function actionVendoritemview($id)
    {
        $searchModel = new VendorItemSearch();

        $dataProvider = $searchModel->searchVendor(
            Yii::$app->request->queryParams,
            Yii::$app->request->get('id')
        );

        return $this->render('vendoritemview', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPassword($id){
    
        $model = $this->findModel($id);

        $model->scenario = 'change';

        if ($model->load(Yii::$app->request->post())) {
            
            $model->vendor_password = Yii::$app->getSecurity()->generatePasswordHash($model->vendor_password);

            if($model->save()){
                Yii::$app->session->setFlash('success', 'Password changed successfully!');
            
                return $this->redirect(['index']);    
            }
        }

        $model->vendor_password = '';

        return $this->render('password',[
            'model' => $model
        ]);
    }

    /**
     * Creates a new Vendor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendor();
        $model->scenario = 'register';

        $model->commision = Siteinfo::info('commission');

        if ($model->load(Yii::$app->request->post())) {

            $vendor_day_off = Yii::$app->request->post('vendor_day_off');

            if(is_array($vendor_day_off)) {
                $model->day_off = implode(',', $vendor_day_off);
            }else{
                $model->day_off = '';
            }

            $vendor = Yii::$app->request->post('Vendor');

            $model->vendor_emergency_contact_name = $vendor['vendor_emergency_contact_name'];
            $model->vendor_emergency_contact_email= $vendor['vendor_emergency_contact_email'];
            $model->vendor_emergency_contact_number= $vendor['vendor_emergency_contact_number'];

            $model->vendor_public_email= $vendor['vendor_public_email'];
            
            $model->vendor_status = (Yii::$app->request->post()['Vendor']['vendor_status']) ? 'Active' : 'Deactive';
            $model->approve_status = 'Yes';
            $model->vendor_contact_number = implode(',', $vendor['vendor_contact_number']);

            $model->slug = Yii::$app->request->post()['Vendor']['vendor_name'];
            $model->slug = str_replace(' ', '-', $model->slug);

            $vendor_password = Yii::$app->getSecurity()->generatePasswordHash($vendor['vendor_password']);

            if(Yii::$app->request->post('image')) {

                $temp_folder = sys_get_temp_dir().'/'; 

                $image_name = Yii::$app->security->generateRandomString();
                $image_extension = '.png';
                $content_type = 'image/png';

                $base64string = str_replace('data:image/png;base64,', '', Yii::$app->request->post('image'));

                //save to temp folder 
                file_put_contents($temp_folder . $image_name . $image_extension, base64_decode($base64string));

                //save to s3
                $awsResult = Yii::$app->resourceManager->save(
                    null, //file upload object  
                    Vendor::UPLOADFOLDER . $image_name . $image_extension, // name
                    [], //options 
                    $temp_folder . $image_name . $image_extension, // source file
                    $content_type
                );

                $model->vendor_logo_path = $image_name . $image_extension;             
            }            

            if ($model->save(false)) {
                
                //add categories
                if(!$vendor['category_id']) {
                    $vendor['category_id'] = [];
                }

                foreach ($vendor['category_id'] as $key => $value) {
                   $vc = new VendorCategory;
                   $vc->vendor_id = $model->vendor_id;
                   $vc->category_id = $value;
                   $vc->save();
                }

                //public phone 
                $phones = Yii::$app->request->post('phone');

                if(!$phones) {
                    $phones = [];
                }

                foreach ($phones as $key => $value) {
                   $vp = new VendorPhoneNo;
                   $vp->vendor_id = $model->vendor_id;
                   $vp->phone_no = $value['phone_no'];
                   $vp->type = $value['type'];
                   $vp->save();
                }

                //remove old alert emails
                VendorOrderAlertEmails::deleteAll(['vendor_id' => $model->vendor_id]);

                //save vendor order alert email
                $vendor_order_alert_emails = Yii::$app->request->post('vendor_order_alert_emails');

                if($vendor_order_alert_emails) {
                    foreach ($vendor_order_alert_emails as $key => $value) {
                        $email = new VendorOrderAlertEmails;
                        $email->vendor_id = $model->vendor_id;
                        $email->email_address = $value;
                        $email->save();
                    }
                }

                //Add to log
                Yii::info('[New Vendor] '. Yii::$app->user->identity->admin_name .' created new vendor '.$model['vendor_name'], __METHOD__);

                //Send Email
                Yii::$app->mailer->compose([
                    "html" => "vendor/package-subscribe"
                        ],[
                    "user" => $model->vendor_name,
                    "vendorEmail" => $model->vendor_contact_email,
                    "vendorPassword" => $vendor['vendor_password'],
                ])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($model->vendor_contact_email)
                ->setSubject('Welcome '.$model['vendor_name'])
                ->send();
            }

            $command=Vendor::updateAll(['vendor_password' => $vendor_password],'vendor_id= '.$model->id);
            Yii::$app->session->setFlash('success', 'Vendor created successfully!');

            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            $main_categories = Category::find()
                ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
                ->where([
                    '{{%category_path}}.level' => 0,
                    'trash' =>'Default'
                ])
                ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
                ->all();

            return $this->render('create', [
                'model' => $model,
                'main_categories' => ArrayHelper::map($main_categories,'category_id','category_name')
            ]);
        }
    }

    /**
     * Updates an existing Vendor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */

     /*
      *  Vendor Subscription Here Update or Save and Send Mail.
      */
    public function actionUpdate($id)
    {
        $base = Yii::$app->basePath;
        $len = rand(1, 1000);
        $model = $this->findModel($id);
        $model->scenario = 'vendorUpdate';
       
        // Current logo
        $exist_logo_image = $model->vendor_logo_path;

        // Current Phone numbers
        $vendor_contact_number = explode(',', $model['vendor_contact_number']);

        if ($model->load(Yii::$app->request->post())) {

            $vendor_day_off = Yii::$app->request->post('vendor_day_off');

            if(is_array($vendor_day_off)) {
                $model->day_off = implode(',', $vendor_day_off);
            }else{
                $model->day_off = '';
            }

            $vendor = Yii::$app->request->post('Vendor');

            $model->slug = Yii::$app->request->post()['Vendor']['vendor_name'];
            $model->slug = str_replace(' ', '-', $model->slug);
            $model->vendor_status = (Yii::$app->request->post()['Vendor']['vendor_status']) ? 'Active' : 'Deactive';
            $model->approve_status = 'Yes';
            $model->vendor_contact_number = implode(',', $model->vendor_contact_number);

            //remove old categories
            VendorCategory::deleteAll(['vendor_id' => $model->vendor_id]);

            //add categories
            if(!$vendor['category_id']) {
                $vendor['category_id'] = [];
            }

            foreach ($vendor['category_id'] as $key => $value) {
               $vc = new VendorCategory;
               $vc->vendor_id = $model->vendor_id;
               $vc->category_id = $value;
               $vc->save();
            }

            //remove old alert emails
            VendorOrderAlertEmails::deleteAll(['vendor_id' => $id]);

            //save vendor order alert email
            $vendor_order_alert_emails = Yii::$app->request->post('vendor_order_alert_emails');

            if($vendor_order_alert_emails) {
                foreach ($vendor_order_alert_emails as $key => $value) {
                    $email = new VendorOrderAlertEmails;
                    $email->vendor_id = $id;
                    $email->email_address = $value;
                    $email->save();
                }
            }

            /*if(Yii::$app->request->post('image')) {

                $temp_folder = sys_get_temp_dir().'/'; 

                $image_name = Yii::$app->security->generateRandomString();
                $image_extension = '.png';
                $content_type = 'image/png';

                $base64string = str_replace('data:image/png;base64,', '', Yii::$app->request->post('image'));

                //save to temp folder 
                file_put_contents($temp_folder . $image_name . $image_extension, base64_decode($base64string));

                //save to s3
                $awsResult = Yii::$app->resourceManager->save(
                    null, //file upload object  
                    Vendor::UPLOADFOLDER . $image_name . $image_extension, // name
                    [], //options 
                    $temp_folder . $image_name . $image_extension, // source file
                    $content_type
                );

                $model->vendor_logo_path = $image_name . $image_extension;

                //delete old image 
                Yii::$app->resourceManager->delete("vendor_logo/" . $exist_logo_image);                
            }*/

            if($model->save(false)) {

                //public phone 
                VendorPhoneNo::deleteAll(['vendor_id' => $model->vendor_id]);

                $phones = Yii::$app->request->post('phone');

                if(!$phones) {
                    $phones = [];
                }

                foreach ($phones as $key => $value) {
                   $vp = new VendorPhoneNo;
                   $vp->vendor_id = $model->vendor_id;
                   $vp->phone_no = $value['phone_no'];
                   $vp->type = $value['type'];
                   $vp->save();
                }

                Yii::$app->session->setFlash('success', 'Vendor updated successfully!');

                return $this->redirect(['index']);
            }

        } else {

            $day_off = explode(',', $model->day_off);

            if (($model->commision) > 1) {
            } else {
                $model->commision = Siteinfo::info('commission');
            }

            //get vendor order notification email address
            $vendor_order_alert_emails = VendorOrderAlertEmails::find()
                ->where(['vendor_id' => $id])
                ->all();

            $vendor_category = VendorCategory::findAll(['vendor_id' => $model->vendor_id]);

            $model->category_id = ArrayHelper::map($vendor_category, 'category_id', 'category_id');

            $main_categories = Category::find()
                ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
                ->where([
                    '{{%category_path}}.level' => 0,
                    'trash' =>'Default'
                ])
                ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
                ->all();

            return $this->render('update', [
                'model' => $model,
                'main_categories' => ArrayHelper::map($main_categories,'category_id','category_name'),
                'vendor_contact_number' => $vendor_contact_number,
                'vendor_order_alert_emails' => $vendor_order_alert_emails,
                'day_off' => $day_off,
                'phones' => VendorPhoneNo::findAll(['vendor_id' => $model->vendor_id])
            ]);
        }
    }

    /**
     * Deletes an existing Vendor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        //Shouldn't be able to delete a vendor who has orders
        $count = Suborder::find()
            ->joinWith('order')
            ->where(['{{%suborder}}.vendor_id' => $id])
            ->andWhere(['!=', '{{%order}}.order_transaction_id', ''])
            ->count();

        if($count) {
            Yii::$app->session->setFlash('danger', 'You can\'t delete a vendor who has orders!');
            return $this->redirect(['vendor/index']);
        }

        $this->findModel($id)->delete();

        //vendor items 
        $sub_query = 'item_id IN (select item_id from {{%vendor_item}} where vendor_id="'.$id.'")';

        VendorItemCapacityException::deleteAll($sub_query);
        Image::deleteAll($sub_query);
        Vendoritempricing::deleteAll($sub_query);
        VendorItemThemes::deleteAll($sub_query);
        VendorItemToCategory::deleteAll($sub_query);
        CustomerCart::deleteAll($sub_query);
        Priorityitem::deleteAll($sub_query);
        EventItemlink::deleteAll($sub_query);
        Featuregroupitem::deleteAll($sub_query);

        //vendor related data 
        VendorCategory::deleteAll(['vendor_id' => $id]);
        BlockedDate::deleteAll(['vendor_id' => $id]);
        DeliveryTimeSlot::deleteAll(['vendor_id' => $id]);
        VendorLocation::deleteAll(['vendor_id' => $id]);
        VendorOrderAlertEmails::deleteAll(['vendor_id' => $id]);
        VendorItem::deleteAll(['vendor_id' => $id]);

        Yii::$app->session->setFlash('success', 'Vendor details deleted successfully!');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Vendor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Vendor the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function prifindModel($id)
    {
        if (($primodel = Prioritylog::findOne($id)) !== null) {
            return $primodel;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEmailcheck()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $subcategory = Vendor::find()->select('vendor_contact_email')
          ->where(['vendor_contact_email' => $data['id']])
          ->andwhere(['trash' => 'default'])
          ->all();
        echo $result = count($subcategory);
    }

    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        $command=Vendor::updateAll(['vendor_status' => $status],'vendor_id= '.$data['id']);
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionVendornamecheck()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $vendorname = Vendor::find()->select('vendor_name')
          ->where(['vendor_name' => $data['vendor_name']])
          ->andwhere(['trash' => 'Default'])
          ->all();
        return $result = count($vendorname);
    }

    /**
    * Save vendor logo from update and create page
    *
    * @return json
    */
    public function actionVendorLogo() 
    {
        $vendor_id = Yii::$app->request->post('vendor_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        $posted_data = Yii::$app->request->post('Vendor');

        //validate
        if(!$is_autosave) {
            $errors = Vendor::validate_vendor_logo($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 

         //if new item 
        if(!$vendor_id) {
            $model = new Vendor();         
        } else {
            $model = Vendor::find()
                ->where(['vendor_id' => $vendor_id])
                ->one();
        }
        
        //load posted data to model 
        $model->load(['Vendor' => $posted_data]);

        //data posted as array but saving in single column 
        $vendor_day_off = Yii::$app->request->post('vendor_day_off');

        if(is_array($vendor_day_off)) {
            $model->day_off = implode(',', $vendor_day_off);
        }else{
            $model->day_off = '';
        }

        $model->vendor_contact_number = implode(',', $model->vendor_contact_number);

        //save data without validation 
        $model->save(false);

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'vendor_id' => $model->vendor_id,
            'edit_url' => Url::to(['vendor/update', 'id' => $model->vendor_id])
        ];
    }

    /**
    * Save vendor basic info from update and create page
    *
    * @return json
    */
    public function actionBasicInfo() 
    {
        $vendor_id = Yii::$app->request->post('vendor_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        $posted_data = Yii::$app->request->post('Vendor');

        //validate
        if(!$is_autosave) {
            $errors = Vendor::validate_basic_info($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 
        
        $model = Vendor::find()
            ->where(['vendor_id' => $vendor_id])
            ->one();
    
        //load posted data to model 
        $model->load(['Vendor' => $posted_data]);

        //data posted as array but saving in single column 
        $vendor_day_off = Yii::$app->request->post('vendor_day_off');

        if(is_array($vendor_day_off)) {
            $model->day_off = implode(',', $vendor_day_off);
        }else{
            $model->day_off = '';
        }

        $model->vendor_contact_number = implode(',', $model->vendor_contact_number);

        //save data without validation 
        $model->save(false);

        //public phone 
        VendorPhoneNo::deleteAll(['vendor_id' => $model->vendor_id]);

        $phones = Yii::$app->request->post('phone');

        if(!$phones) {
            $phones = [];
        }

        foreach ($phones as $key => $value) {
           $vp = new VendorPhoneNo;
           $vp->vendor_id = $model->vendor_id;
           $vp->phone_no = $value['phone_no'];
           $vp->type = $value['type'];
           $vp->save();
        }

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'vendor_id' => $model->vendor_id
        ];
    }

    /**
    * Save vendor main info from update and create page
    *
    * @return json
    */
    public function actionMainInfo() 
    {
        $vendor_id = Yii::$app->request->post('vendor_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        $posted_data = Yii::$app->request->post('Vendor');

        //validate
        if(!$is_autosave) {
            $errors = Vendor::validate_main_info($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 
        
        $model = Vendor::find()
            ->where(['vendor_id' => $vendor_id])
            ->one();
    
        //load posted data to model 
        $model->load(['Vendor' => $posted_data]);

        //data posted as array but saving in single column 
        $vendor_day_off = Yii::$app->request->post('vendor_day_off');

        if(is_array($vendor_day_off)) {
            $model->day_off = implode(',', $vendor_day_off);
        }else{
            $model->day_off = '';
        }

        $model->vendor_contact_number = implode(',', $model->vendor_contact_number);

        //save data without validation 
        $model->save(false);
       
        //remove old categories
        VendorCategory::deleteAll(['vendor_id' => $model->vendor_id]);

        //add categories
        if(!$posted_data['category_id']) {
            $posted_data['category_id'] = [];
        }

        foreach ($posted_data['category_id'] as $key => $value) {
           $vc = new VendorCategory;
           $vc->vendor_id = $model->vendor_id;
           $vc->category_id = $value;
           $vc->save();
        }

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'vendor_id' => $model->vendor_id
        ];
    }

    /**
    * Save vendor additional info from update and create page
    *
    * @return json
    */
    public function actionAdditionalInfo() 
    {
        $vendor_id = Yii::$app->request->post('vendor_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        $posted_data = Yii::$app->request->post('Vendor');

        //validate
        if(!$is_autosave) {
            $errors = Vendor::validate_additional_info($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 
        
        $model = Vendor::find()
            ->where(['vendor_id' => $vendor_id])
            ->one();
    
        //load posted data to model 
        $model->load(['Vendor' => $posted_data]);

        //data posted as array but saving in single column 
        $vendor_day_off = Yii::$app->request->post('vendor_day_off');

        if(is_array($vendor_day_off)) {
            $model->day_off = implode(',', $vendor_day_off);
        }else{
            $model->day_off = '';
        }

        $model->vendor_contact_number = implode(',', $model->vendor_contact_number);

        //save data without validation 
        $model->save(false);

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'vendor_id' => $model->vendor_id
        ];
    }

    /**
    * Save vendor social info from update and create page
    *
    * @return json
    */
    public function actionSocialInfo() 
    {
        $vendor_id = Yii::$app->request->post('vendor_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        $posted_data = Yii::$app->request->post('Vendor');

        //validate
        if(!$is_autosave) {
            $errors = Vendor::validate_social_info($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 
        
        $model = Vendor::find()
            ->where(['vendor_id' => $vendor_id])
            ->one();
    
        //load posted data to model 
        $model->load(['Vendor' => $posted_data]);

        //data posted as array but saving in single column 
        $vendor_day_off = Yii::$app->request->post('vendor_day_off');

        if(is_array($vendor_day_off)) {
            $model->day_off = implode(',', $vendor_day_off);
        }else{
            $model->day_off = '';
        }

        $model->vendor_contact_number = implode(',', $model->vendor_contact_number);

        //save data without validation 
        $model->save(false);

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'vendor_id' => $model->vendor_id
        ];
    }

    /**
    * Save vendor email addresses from update and create page
    *
    * @return json
    */
    public function actionEmailAddresses() 
    {
        $vendor_id = Yii::$app->request->post('vendor_id');
        $is_autosave = Yii::$app->request->post('is_autosave');

        $posted_data = Yii::$app->request->post('Vendor');

        //validate
        if(!$is_autosave) {
            $errors = Vendor::validate_email_addresses($posted_data);

            if($errors) {
                \Yii::$app->response->format = 'json';
                
                return [
                    'errors' => $errors
                ];
            }                
        } 
        
        $model = Vendor::find()
            ->where(['vendor_id' => $vendor_id])
            ->one();
    
        //load posted data to model 
        $model->load(['Vendor' => $posted_data]);

        //data posted as array but saving in single column 
        $vendor_day_off = Yii::$app->request->post('vendor_day_off');

        if(is_array($vendor_day_off)) {
            $model->day_off = implode(',', $vendor_day_off);
        }else{
            $model->day_off = '';
        }

        $model->vendor_contact_number = implode(',', $model->vendor_contact_number);

        //save data without validation 
        $model->save(false);

        //remove old alert emails
        VendorOrderAlertEmails::deleteAll(['vendor_id' => $model->vendor_id]);

        //save vendor order alert email
        $vendor_order_alert_emails = Yii::$app->request->post('vendor_order_alert_emails');

        if($vendor_order_alert_emails) {
            foreach ($vendor_order_alert_emails as $key => $value) {
                $email = new VendorOrderAlertEmails;
                $email->vendor_id = $model->vendor_id;
                $email->email_address = $value;
                $email->save();
            }
        }

        \Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'vendor_id' => $model->vendor_id
        ];
    }   
    
    /**
     * Validate whole form on click of complete button 
     *
     * @return json
     */
    public function actionVendorValidate()
    {
        \Yii::$app->response->format = 'json';
        
        $posted_data = Yii::$app->request->post('Vendor');
        
        $errors = Vendor::validate_form($posted_data);

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

    public function actionCropedImageUpload()
    {
        // Set max execution time 3 minutes.
        set_time_limit(3 * 60); 

        $temp_folder = sys_get_temp_dir().'/'; 

        $image_name = Yii::$app->security->generateRandomString();
        $image_extension = '.png';
        $content_type = 'image/png';

        $base64string = str_replace('data:image/png;base64,', '', Yii::$app->request->post('image'));

        //save to temp folder 
        file_put_contents($temp_folder . $image_name . $image_extension, base64_decode($base64string));

        //save to s3
        $awsResult = Yii::$app->resourceManager->save(
            null, //file upload object  
            Vendor::UPLOADFOLDER . $image_name . $image_extension, // name
            [], //options 
            $temp_folder . $image_name . $image_extension, // source file
            $content_type
        );

        if (!$awsResult) {
            return [
                'error' => 'File not uploaded successfully!'
            ];    
        }

        //delete temp file
        unlink($temp_folder . $image_name . $image_extension);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'image_url' => Yii::getAlias("@s3/vendor_logo/") . $image_name . $image_extension,
            'image' => $image_name . $image_extension
        ];
    }    
}
