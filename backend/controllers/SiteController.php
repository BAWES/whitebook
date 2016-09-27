<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Session;
use common\models\Package;
use common\models\Category;
use common\models\Siteinfo;
use common\models\VendorOrderAlertEmails;
use backend\models\Vendor;
use backend\models\Vendoritem;
use backend\models\VendorLogin;
use backend\models\VendorPassword;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Security;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'recoverypassword'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $vendor_id = Yii::$app->user->getId();
        $vendoritemcnt = Vendoritem::vendoritemcount();
        $monthitemcnt = Vendoritem::vendoritemmonthcount();
        $dateitemcnt = Vendoritem::vendoritemdatecount();
        $packageenddate = Vendor::getVendor_packagedate($vendor_id);

        return $this->render('index', [
            'vendoritemcnt' => $vendoritemcnt,
            'monthitemcnt' => $monthitemcnt,
            'dateitemcnt' => $dateitemcnt,
            'packageenddate' => $packageenddate
        ]);
    }


    public function actionLogin() {

        $this->layout = "login";

        $model = new VendorLogin();

        if(!Yii::$app->user->isGuest){

            $this->redirect(['site/index']);

        }else{

            if ($model->load(Yii::$app->request->post()) && $model->login()) {

                $vendor_id = Yii::$app->user->getId();

                $package = Vendor::packageCheck($vendor_id);

                $status = Vendor::statusCheck($vendor_id);

                if(!$status){

                    $session = Yii::$app->session;
                    Yii::$app->user->logout();
                    $session->destroy();

                    Yii::$app->session->setFlash('danger', "Kindly contact admin, account deactivated!");

                    Yii::warning('[Account Deactivated - '. Yii::$app->user->identity->vendor_name .'] '. Yii::$app->user->identity->vendor_name .' needs to contact admin, account deactivated', __METHOD__);

                    return $this->redirect(['site/login']);
                }

                if($package){

                    Yii::info('[Vendor Login - '. Yii::$app->user->identity->vendor_name .'] '. Yii::$app->user->identity->vendor_name .' has logged in to manage their items', __METHOD__);

                    return $this->redirect(['site/index']);

                } else {

                    $session = Yii::$app->session;
                    Yii::$app->user->logout();
                    $session->destroy();

                    Yii::$app->session->setFlash('danger', "Kindly contact admin, package expired!");

                    Yii::warning('[Vendor Package Expired - '. Yii::$app->user->identity->vendor_name .'] '. Yii::$app->user->identity->vendor_name .' needs contact admin, package expired', __METHOD__);

                    return $this->redirect(['site/login']);
                }

            } else {

                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionChangepassword()
    {
        $session = Yii::$app->session;

        $users_tbl = Vendor::find()->where(['vendor_contact_email' => Vendor::getVendor('vendor_contact_email')])->one();

        $model = new VendorPassword;
        $model->scenario = 'change';

        $vendoritemcnt = Vendoritem::vendoritemcount();
        $monthitemcnt = Vendoritem::vendoritemmonthcount();
        $dateitemcnt = Vendoritem::vendoritemdatecount();
        $packageenddate = Vendor::getVendor_packagedate(Yii::$app->user->identity->id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $form = Yii::$app->request->post('VendorPassword');

            if(!Yii::$app->getSecurity()->validatePassword($form['old_password'], $users_tbl['vendor_password'])) {
                Yii::$app->session->setFlash('danger', "Old password does not match!");
                $this->redirect(['changepassword']);
                return false;

            } else if($form['new_password'] !== $form['confirm_password']) {

                Yii::$app->session->setFlash('danger', "Old password and new password does not match!");
                $this->redirect(['changepassword']);
                return false;

            } else {

                $users_tbl->scenario = 'change';
                $users_tbl->vendor_password = Yii::$app->getSecurity()->generatePasswordHash($form['new_password']);
                $users_tbl->save();

                Yii::$app->session->setFlash('success', "SuccessFully changed your password!");

                return $this->render('index',[
                    'vendoritemcnt' => $vendoritemcnt,
                    'monthitemcnt' => $monthitemcnt,
                    'dateitemcnt' => $dateitemcnt,
                    'packageenddate' => $packageenddate
                ]);
            }
        }

        return $this->render('changepasswords', ['model'=> $model]);
    }

    public function actionRecoverypassword()
    {
        $model = new VendorPassword;

        if($model->load(Yii::$app->request->post()) && $model->validate()) {

            $form = Yii::$app->request->post('VendorPassword');

            $rows = Vendor::find()->select('vendor_contact_email')
    			->where(['vendor_contact_email'=>$form['vendor_contact_email']])
    			->asArray()
    			->all();

            if(!empty($rows)) {

                $length = 10;
                $randomString = substr(str_shuffle(md5(time())),0,$length);
                $password = Yii::$app->getSecurity()->generatePasswordHash($randomString);
                $message = 'User id : '.$form['vendor_contact_email'] . ', Your New Password : '.$randomString;

                Yii::$app->mailer->compose([
                        "html" => "vendor/password-reset"
                            ],[
                        "message" => $message,
                        "user" => 'Vendor'
                    ])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($form['vendor_contact_email'])
                    ->setSubject('Vendor password recovery')
                    ->send();

				$command = Vendor::updateAll(['vendor_password' => $password],['vendor_contact_email= '.$form['vendor_contact_email']]);

                if($command) {
                    Yii::$app->session->setFlash('success', 'New password send to your registered email-id.');
                }

                return $this->redirect('recoverypassword');

            } else{
                Yii::$app->session->setFlash('danger', "email is not registered!");
                return $this->redirect('recoverypassword');
            }
        }

        return $this->renderPartial('passwords', ['model' => $model]);
    }

    public function actionProfile($id=false) {

        $siteinfo = Siteinfo::find()->all();
        $to = $siteinfo[0]['email_id']; // admin email

        $vendor_id = Yii::$app->user->getId();

        $model = Vendor::findOne($vendor_id);
        $model->scenario = 'vendorprofile';
        $base = Yii::$app->basePath;
        $len = rand(1,1000);

        $vendor_category = explode(",", $model['category_id']);
        $v_category = Category::find()->select('category_name')
        ->where(['IN','category_id',$vendor_category])->asArray()->all();

        foreach ($v_category as $key => $value) {
            $vendor_categories[] = $value['category_name'];
        }

        // Current logo
        $exist_logo_image = $model['vendor_logo_path'];

        // Current Phone numbers
        $vendor_contact_number = explode(',',$model['vendor_contact_number']);

        if ($model->load(Yii::$app->request->post())) {

            $vendor_working_days = Yii::$app->request->post('vendor_working_days');

            if(is_array($vendor_working_days)) {
                $model->working_days = implode(',', $vendor_working_days);    
            }else{
                $model->working_days = '';
            }
            
            $vendor_working_am_pm_from = Yii::$app->request->post('vendor_working_am_pm_from');
            $vendor_working_am_pm_to = Yii::$app->request->post('vendor_working_am_pm_to');

            $vendor = Yii::$app->request->post('Vendor');
            $model->vendor_contact_number = implode(',', $vendor['vendor_contact_number']);
            $model->vendor_working_hours = $vendor['vendor_working_hours'].':'.$vendor['vendor_working_min'].':'.$vendor_working_am_pm_from;
            $model->vendor_working_hours_to = $vendor['vendor_working_hours_to'].':'.$vendor['vendor_working_min_to'].':'.$vendor_working_am_pm_to;

            //remove old alert emails 
            VendorOrderAlertEmails::deleteAll(['vendor_id' => $vendor_id]);

            //save vendor order alert email 
            $vendor_order_alert_emails = Yii::$app->request->post('vendor_order_alert_emails');

            if($vendor_order_alert_emails) {
                foreach ($vendor_order_alert_emails as $key => $value) {
                    $email = new VendorOrderAlertEmails;
                    $email->vendor_id = $vendor_id;
                    $email->email_address = $value;
                    $email->save();
                }
            }

            $file = UploadedFile::getInstances($model, 'vendor_logo_path');

            if ($file) {
                foreach ($file as $files) {
                    if ($files instanceof yii\web\UploadedFile) {
                        $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                        //Resize file using imagine
                        $resize = true;

                        if ($resize) {
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
                        if ($awsResult) {
                            $model->vendor_logo_path = $filename;
                        }
                    }
                }
            } else {
                $model->vendor_logo_path = $exist_logo_image;
            }

            if($model->save()) {

                $v_name = $model['vendor_name'];
                Yii::info('[Vendor Profile Updated] Vendor updated profile information', __METHOD__);
                Yii::$app->session->setFlash('success', "Successfully updated your profile!");
                return $this->redirect(['index']);

            } else {
                Yii::$app->session->setFlash('danger', "Something went wrong!");
                return $this->render('profile', ['model' => $model]);
            }
        }

        $working_days = explode(',', $model->working_days);

        //get vendor order notification email address 
        $vendor_order_alert_emails = VendorOrderAlertEmails::find()
            ->where(['vendor_id' => $vendor_id])
            ->all();

        return $this->render('profile', [
            'model' => $model,
            'vendor_order_alert_emails' => $vendor_order_alert_emails,
            'vendor_contact_number' => $vendor_contact_number,
            'vendor_categories' => $vendor_categories,
            'working_days' => $working_days
        ]);
    }

    public function actionImageorder() {

        if (!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();

        $i = 1;
        foreach ($data['sort'] as $order => $value) {
            $command = \common\models\Image::updateAll(['vendorimage_sort_order' => $i],'image_id= '.$value);
            ++$i;
        }
        die;
    }
}
