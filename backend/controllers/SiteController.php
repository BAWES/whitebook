<?php
namespace backend\controllers;

use common\models\Booking;
use common\models\VendorWorkingTiming;
use Yii;
use yii\db\Query;
use yii\helpers\Security;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\Session;
use yii\filters\AccessControl;
use common\models\Category;
use common\models\VendorCategory;
use common\models\Siteinfo;
use common\models\VendorOrderAlertEmails;
use common\models\Suborder;
use common\models\VendorPhoneNo;
use backend\models\Vendor;
use backend\models\VendorItem;
use backend\models\VendorLogin;
use backend\models\VendorPassword;

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

        $vendoritemcnt = VendorItem::vendoritemcount($vendor_id);
        $monthitemcnt = VendorItem::vendoritemmonthcount($vendor_id);
        $dateitemcnt = VendorItem::vendoritemdatecount($vendor_id);

        $earning_total = Booking::find()
            ->where('transaction_id is not NULL')
            ->andWhere(['vendor_id' => $vendor_id,'booking_status'=>1])
            ->sum('total_vendor');

        $vendor = Vendor::findOne($vendor_id);

        return $this->render('index', [
            'vendoritemcnt' => $vendoritemcnt,
            'monthitemcnt' => $monthitemcnt,
            'dateitemcnt' => $dateitemcnt,
            'earning_total' => number_format($earning_total, 3).' KD',
            'vendor_payable' => number_format($vendor->vendor_payable, 3).' KD'
        ]);
    }


    public function actionLogin() {

        if(!Yii::$app->user->isGuest){
            $this->redirect(['site/index']);
        }

        $this->layout = "login";

        $model = new VendorLogin();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $vendor_id = Yii::$app->user->getId();

            $status = Vendor::statusCheck($vendor_id);

            if(!$status){

                Yii::warning('[Account Deactivated - '. Yii::$app->user->identity->vendor_name .'] '. Yii::$app->user->identity->vendor_name .' needs to contact admin, account deactivated', __METHOD__);

                $session = Yii::$app->session;
                Yii::$app->user->logout();
                $session->destroy();
                
                Yii::$app->session->setFlash('danger', "Kindly contact admin, account deactivated!");

                return $this->redirect(['site/login']);
            }

            Yii::info('[Vendor Login - '. Yii::$app->user->identity->vendor_name .'] '. Yii::$app->user->identity->vendor_name .' has logged in to manage their items', __METHOD__);

            return $this->goBack(['site/index']);

        } else {

            return $this->render('login', [
                'model' => $model,
            ]);
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

        $vendoritemcnt = VendorItem::vendoritemcount();
        $monthitemcnt = VendorItem::vendoritemmonthcount();
        $dateitemcnt = VendorItem::vendoritemdatecount();
        
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
                    'dateitemcnt' => $dateitemcnt
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

        $to = Yii::$app->params['adminEmail'];

        $vendor_id = Yii::$app->user->getId();

        $model = Vendor::findOne($vendor_id);
        $model->scenario = 'vendorprofile';
        $base = Yii::$app->basePath;
        $len = rand(1,1000);

        $v_category = VendorCategory::find()
            ->select('{{%category}}.category_name')
            ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_category}}.category_id')
            ->where(['{{%vendor_category}}.vendor_id' => $vendor_id])
            ->asArray()
            ->all();

        $vendor_categories = [];

        foreach ($v_category as $key => $value) {
            $vendor_categories[] = $value['category_name'];
        }

        // Current logo
        $exist_logo_image = $model['vendor_logo_path'];

        // Current Phone numbers
        $vendor_contact_number = explode(',',$model['vendor_contact_number']);

        if ($model->load(Yii::$app->request->post())) {

            $vendor_day_off = Yii::$app->request->post('vendor_day_off');

            if (is_array($vendor_day_off)) {
                $model->day_off = implode(',', $vendor_day_off);
            } else {
                $model->day_off = '';
            }
            
            $vendor = Yii::$app->request->post('Vendor');
            $model->vendor_contact_number = implode(',', $vendor['vendor_contact_number']);

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
            
            if($model->save()) {

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

                $v_name = $model['vendor_name'];
                
                Yii::info('[Vendor Profile Updated] Vendor updated profile information', __METHOD__);
                Yii::$app->session->setFlash('success', "Successfully updated your profile!");
                return $this->redirect(['index']);

            } else {
                Yii::$app->session->setFlash('danger', "Something went wrong!");
                return $this->render('profile', ['model' => $model]);
            }
        }

        $day_off = explode(',', $model->day_off);

        //get vendor order notification email address 
        $vendor_order_alert_emails = VendorOrderAlertEmails::find()
            ->where(['vendor_id' => $vendor_id])
            ->all();

        return $this->render('profile', [
            'model' => $model,
            'vendor_order_alert_emails' => $vendor_order_alert_emails,
            'vendor_contact_number' => $vendor_contact_number,
            'vendor_categories' => $vendor_categories,
            'day_off' => $day_off,
            'phones' => VendorPhoneNo::findAll(['vendor_id' => $model->vendor_id]),
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
