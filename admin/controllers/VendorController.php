<?php
namespace admin\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Prioritylog;
use common\models\PrioritylogSearch;
use common\models\Siteinfo;
use admin\models\Vendor;
use admin\models\Authitem;
use admin\models\Category;
use admin\models\VendorSearch;
use common\models\Vendorpackages;
use admin\models\Package;
use common\models\VendoritemcapacityexceptionSearch;
use common\models\VendoritemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\VendorpackagesSearch;

/**
 * VendorController implements the CRUD actions for Vendor model.
 */
class VendorController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                   [
                       'actions' => [],
                       'allow' => true,
                       'roles' => ['?'],
                   ],
                   [
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'loadcategory', 'loadsubcategory', 'vendoritemview', 'vendorname', 'changepackage', 'changeeditpackage', 'emailcheck', 'loadpackagedate', 'packageupdate', 'vendornamecheck'],
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
     * Lists all Vendor models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '22');
        if (yii::$app->user->can($access)) {
            $searchModel = new VendorSearch();
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

    public function actionView($id)
    {
        $searchModel = new VendoritemSearch();
        $dataProvider = $searchModel->searchviewVendor(Yii::$app->request->queryParams, $_GET['id']);

        $searchModel1 = new PrioritylogSearch();
        $dataProvider1 = $searchModel1->vendorviewsearch(Yii::$app->request->queryParams, $id);

        $searchModel2 = new VendorpackagesSearch();
        $dataProvider2 = $searchModel2->vendorviewsearch(Yii::$app->request->queryParams, $id);

        $searchModel3 = new VendoritemcapacityexceptionSearch();
        $dataProvider3 = $searchModel3->search(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'searchModel3' => $searchModel3,
            'dataProvider' => $dataProvider,
            'dataProvider1' => $dataProvider1,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
        ]);
    }

    public function actionVendoritemview($id)
    {
        $searchModel = new VendoritemSearch();
        $dataProvider = $searchModel->searchVendor(Yii::$app->request->queryParams, $_GET['id']);

        return $this->render('vendoritemview', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $access = Authitem::AuthitemCheck('1', '22');

        $package = Package::loadpackage();

        if (yii::$app->user->can($access)) {
            $model = new Vendor();
            $model->scenario = 'register';
            $model_siteinfo = Siteinfo::find()->all();
            foreach ($model_siteinfo as $key => $val) {
                $first_id = $val['commision'];
            }
            if (count($model_siteinfo) == 1) {
                $model->commision = $first_id;
            }
            if ($model->load(Yii::$app->request->post())) {
                $model->vendor_status = (Yii::$app->request->post()['Vendor']['vendor_status']) ? 'Active' : 'Deactive';

                $model->approve_status = 'Yes';
                $vendor = Yii::$app->request->post('Vendor');
                $model->vendor_contact_number = implode(',', $vendor['vendor_contact_number']);
                $model->category_id = implode(',', $vendor['category_id']);

                $model->slug = Yii::$app->request->post()['Vendor']['vendor_name'];
                $model->slug = str_replace(' ', '-', $model->slug);

                $vendor_password = Yii::$app->getSecurity()->generatePasswordHash($vendor['vendor_password']);
                $file = UploadedFile::getInstances($model, 'vendor_logo_path');

                if ($file) {
                    foreach ($file as $files) {
                        if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if($resize){
                                $newTmpName = $files->tempName . "." . $files->extension;

                                $imagine = new \Imagine\Gd\Imagine();
                                $image = $imagine->open($files->tempName);
                                $image->resize($image->getSize()->widen(250));
                                $image->save($newTmpName);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName;
                            }

                            //Save to S3
                            $awsResult = Yii::$app->resourceManager->save($files, Vendor::UPLOADFOLDER . $filename);
                            if($awsResult){
                                $model->vendor_logo_path = $filename;
                            }
                        }
                    }
                }
                if ($model->save(false)) {
                    //Add to log
                    Yii::info('[New Vendor] Admin created new vendor '.$model['vendor_name'], __METHOD__);

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
                echo Yii::$app->session->setFlash('success', 'Vendor created successfully!');

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', ['package' => $package,
                'model' => $model,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
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
        $access = Authitem::AuthitemCheck('2', '22');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $model->scenario = 'vendorUpdate';
            // List all packages
            $package = Package::loadpackage();

            //Current package
            $packname = Package::findOne($model->package_id);
            $present_package = $packname['package_name'];

            // list of categories
            $model->category_id = explode(',', $model->category_id);

            // Current logo
            $exist_logo_image = $model->vendor_logo_path;

            // Current Phone numbers
            $vendor_contact_number = explode(',', $model['vendor_contact_number']);

            if ($model->load(Yii::$app->request->post())) {
                $vendor = Yii::$app->request->post('Vendor');
                $model->slug = Yii::$app->request->post()['Vendor']['vendor_name'];
                $model->slug = str_replace(' ', '-', $model->slug);
                $model->vendor_status = (Yii::$app->request->post()['Vendor']['vendor_status']) ? 'Active' : 'Deactive';
                $model->approve_status = 'Yes';
                $model->vendor_contact_number = implode(',', $model->vendor_contact_number);
                $model->category_id = implode(',', $model->category_id);                            /*
                /*--- Vendor logo ---*/

                $file = UploadedFile::getInstances($model, 'vendor_logo_path');
                if (!empty($file)) {
                    foreach ($file as $files) {
                        if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if($resize){
                                $newTmpName = $files->tempName . "." . $files->extension;

                                $imagine = new \Imagine\Gd\Imagine();
                                $image = $imagine->open($files->tempName);
                                $image->resize($image->getSize()->widen(250));
                                $image->save($newTmpName);

                                //Overwrite old filename for S3 uploading
                                $files->tempName = $newTmpName;
                            }

                            //Save to S3
                            $awsResult = Yii::$app->resourceManager->save($files, Vendor::UPLOADFOLDER . $filename);
                            if($awsResult){
                                $model->vendor_logo_path = $filename;
                            }
                        }
                    }
                }
                else {
                    $model->vendor_logo_path = $exist_logo_image;
                }
                if($model->save(false))
                {
                    Yii::$app->resourceManager->delete("vendor_logo/" . $exist_logo_image);
                    echo Yii::$app->session->setFlash('success', 'Vendor updated successfully!');
                }
                

                return $this->redirect(['index']);
            } else {
                if (($model->commision) > 1) {
                } else {
                    $model_siteinfo = Siteinfo::find()->all();
                    foreach ($model_siteinfo as $key => $val) {
                        $first_id = $val['commision'];
                    }
                    if (count($model_siteinfo) == 1) {
                        $model->commision = $first_id;
                    }
                }

                return $this->render('update', [
                'model' => $model, 'package' => $package, 'vendor_contact_number' => $vendor_contact_number, 'present_package' => $present_package,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
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
        $access = Authitem::AuthitemCheck('3', '22');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();
            echo Yii::$app->session->setFlash('success', 'Vendor details deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
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

    public function actionLoadcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $categoryid = Vendor::find()->select('category_id')->where(['vendor_id' => $data['id']])->one();
        $k = $categoryid->category_id;
        $category = Category::find()->select('category_id,category_name')->where(['category_id' => $k])->all();
        echo  '<option value="">Select...</option>';
        foreach ($category as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }
    public function actionLoadsubcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $subcategory = Category::find()->select('category_id,category_name')->where(['parent_category_id' => $data['id']])->all();
        echo  '<option value="">Select...</option>';
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
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

    public function actionChangepackage()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $user_start_date = ($data['start_date']);
        $user_end_date = ($data['end_date']);
        if (strtotime($data['start_date']) <= strtotime($data['end_date'])) {
            while (strtotime($user_start_date) <= strtotime($user_end_date)) {
                $selected_dates[] = $user_start_date;
                $user_start_date = date('Y-m-d', strtotime('+1 day', strtotime($user_start_date)));
            }
        } else {
            return '2';
            die;
        }
        $package_pricing = Package::loadpackageprice($data['id']);
        $package_name = Package::PackageData($data['id']);
        $datetime = Vendorpackages::find()->where(['vendor_id' => $data['vid']])->asArray()->all();
        /*$datetime = (new \yii\db\Query())->from('{{%vendor_packages}}')->select()->where(['vendor_id' => $data['vid']])->all();*/
        //print_r($datetime);die;
        
        foreach ($datetime as $d) {
            $date = $date1 = $d['package_start_date'];
            $end_date = $end_date1 = $d['package_end_date'];
            while (strtotime($date) <= strtotime($end_date)) {
                $blocked_dates[] = $date;
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            }
        }
        $available = 0;
        if (!empty($blocked_dates)) {
            foreach ($selected_dates as $key => $value) {
                $available = in_array($value, $blocked_dates);
                if ($available) {
                    return '1';
                    die;
                }
            }
        }
        if ($available == 0) {			    


        $vendor_pack = new Vendorpackages();	
        $vendor_pack->vendor_id = $data['vid'];
        $vendor_pack->package_id = $data['id'];
        $vendor_pack->package_price = $package_pricing;
        $vendor_pack->package_start_date = $data['start_date'];
        $vendor_pack->package_end_date = $data['end_date'];
		$vendor_pack->save();
		$packageid = $vendor_pack->id;

            $url = Url::to('/package/packagedelete?id='.$packageid);
            $startshow = date('d/m/Y', strtotime($data['start_date']));
            $endshow = date('d/m/Y', strtotime($data['end_date']));
            $output = '<tr id="tr-'.$packageid.'"><td>'.$package_name.'</td><td>'.$startshow.'</td><td>'.$endshow.'</td><td>'.$package_pricing.'<input type="hidden" id="packedit" value='.$packageid.'></td><td>'.Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['onclick' => 'packagedelete('.$packageid.');', 'title' => 'Delete']).''.Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', ['onclick' => 'packageedit('.$packageid.');', 'title' => 'Edit']).'</td></tr>';

            return $output;
        }
    }

    public function actionChangeeditpackage()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $user_start_date = ($data['start_date']);
        $user_end_date = ($data['end_date']);
        $packedit = $data['packedit'];
        if (strtotime($data['start_date']) <= strtotime($data['end_date'])) {
            while (strtotime($user_start_date) <= strtotime($user_end_date)) {
                $selected_dates[] = $user_start_date;
                $user_start_date = date('Y-m-d', strtotime('+1 day', strtotime($user_start_date)));
            }
        } else {
            return '2';
            die;
        }

        $package_pricing = Package::loadpackageprice($data['id']);
        $package_name = Package::PackageData($data['id']);
                $datetime = Vendorpackages::find()->select(['DATE_FORMAT(package_start_date,"%Y-%m-%d") as package_start_date','DATE_FORMAT(package_end_date,"%Y-%m-%d") as package_end_date'])
          ->where(['vendor_id' => $data['id']])
          ->andwhere(['!=','id',$packedit])
          ->asArray()
          ->all();
          
      /*  $datetime = (new \yii\db\Query())->from('{{%vendor_packages}}')->select(['DATE_FORMAT(package_start_date,"%Y-%m-%d") as package_start_date','DATE_FORMAT(package_end_date,"%Y-%m-%d") AS package_end_date'])->where(['vendor_id' => $data['vid'],['!=', 'id', $packedit]])->all();*/
        
        $blocked_dates = array();
        if (!empty($datetime)) {
            foreach ($datetime as $d) {
                $date = $date1 = $d['package_start_date'];
                $end_date = $end_date1 = $d['package_end_date'];
                while (strtotime($date) <= strtotime($end_date)) {
                    $blocked_dates[] = $date;
                    $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                }
            }
        }
        $available = 0;
        if (!empty($selected_dates)) {
            foreach ($selected_dates as $key => $value) {
                $available = in_array($value, $blocked_dates);
                if ($available) {
                    return '1';
                    die;
                }
            }
        }
        if ($available == 0) {
            $start1 = date('Y/m/d', strtotime($data['start_date']));
            $end1 = date('Y/m/d', strtotime($data['end_date']));
            $user_start_date = ($data['start_date']);
            $user_start_date = ($data['start_date']);

            $command=Vendorpackages::updateAll(['package_id' => $data['id'],'package_start_date' => $start1,'package_end_date' => $end1],'id= '.$packedit);


            $url = Url::to('/package/packagedelete?id='.$packedit);
            $startshow = date('d/m/Y', strtotime($data['start_date']));
            $endshow = date('d/m/Y', strtotime($data['end_date']));
            $output = '<tr id="tr-'.$packedit.'" class="update_row"><td>'.$package_name.'</td><td>'.$startshow.'</td><td>'.$endshow.'<input type="hidden" id="packedit" value='.$packedit.'></td><td>'.$package_pricing.'</td><td>'.Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['onclick' => 'packagedelete('.$packedit.');', 'title' => 'Delete']).''.Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', ['onclick' => 'packageedit('.$packedit.');', 'title' => 'Edit']).'</td></tr>';

            return $output;
        }
    }

    public function actionLoadpackagedate()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        if ($data['id']):
        
        $datetime = Vendorpackages::find()->select(['package_start_date','package_end_date'])
          ->where(['vendor_id' => $data['id']])
          ->asArray()
          ->all();
        $k = array();
        foreach ($datetime as $d) {
            $date = $d['package_start_date'];
            $date = date('Y-m-d', strtotime('-2 day', strtotime($date)));
            $end_date = $d['package_end_date'];
            $end_date = date('Y-m-d', strtotime('-2 day', strtotime($end_date)));
            while (strtotime($date) <= strtotime($end_date)) {
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                $k[] = $date;
            }
        }
        $cnt = count($k);
        $as = '<input type="text" id="vendor-package_start_date" class="form-control" name="Vendor[package_start_date]">';
        $ae = '<input type="text" id="vendor-package_end_date" class="form-control" name="Vendor[package_end_date]">';
        echo json_encode(array('date' => $k, 'count' => $cnt, 'input1' => $as, 'input2' => $ae));
        exit;
        endif;
    }

    public function actionPackageupdate()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        if ($data['packid']):
        
        $packdate = Vendorpackages::find()->select(['package_id','package_start_date','package_end_date'])
          ->where(['vendor_id' => $data['vid']])
          ->andwhere(['id' =>$data['packid']])
          ->asArray()->all();
        
        $package_id = $packdate[0]['package_id'];
        $edit_start_date = date('Y-m-d', strtotime('+0 day', strtotime($packdate[0]['package_start_date'])));
        $edit_end_date = date('Y-m-d', strtotime('+0 day', strtotime($packdate[0]['package_end_date'])));
        if (strtotime($edit_start_date) > strtotime($edit_end_date)) {
            return '2';
            die;
        }
        $datetime = Vendorpackages::find()->select(['package_id','package_start_date','package_end_date'])
          ->where(['vendor_id' => $data['vid']])
          ->andwhere(['id' =>$data['packid']])
          ->asArray()->all();
        $k = array();
        foreach ($datetime as $d) {
            $date = $d['package_start_date'];
            $date = date('Y-m-d', strtotime('-2 day', strtotime($date)));
            $end_date = $d['package_end_date'];
            $end_date = date('Y-m-d', strtotime('-2 day', strtotime($end_date)));
            while (strtotime($date) <= strtotime($end_date)) {
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                $k[] = $date;
            }
        }

        $cnt = count($k);
        $as = '<input type="text" id="edit_start" class="form-control edit_start" name="Vendor[package_start_date]" value="" maxlength="125" placeholder="Start date">';
        $ae = '<input type="text" id="edit_end" class="form-control edit_end" name="Vendor[package_end_date]" value="" maxlength="125" placeholder="End date">';
        echo json_encode(array('date' => $k, 'packid' => $package_id, 'start' => $edit_start_date, 'end' => $edit_end_date, 'input1' => $as, 'input2' => $ae));
        exit;
        endif;
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
        echo $result = count($vendorname);
    }
}
