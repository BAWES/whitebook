<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Session;
use common\models\Package;
use common\models\Vendor;
use common\models\Vendoritem;
use common\models\VendorLogin;
use common\models\VendorPassword;
use common\models\Siteinfo;
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
        $vendor_id=Yii::$app->user->getId();
        $vendoritemcnt=Vendoritem::vendoritemcount();
        $monthitemcnt=Vendoritem::vendoritemmonthcount();
        $dateitemcnt=Vendoritem::vendoritemdatecount();
        $packageenddate=Vendor::getVendor_packagedate($vendor_id);
        return $this->render('index', ['vendoritemcnt' => $vendoritemcnt,'monthitemcnt'=>$monthitemcnt,'dateitemcnt'=>$dateitemcnt,'packageenddate'=>$packageenddate]);
    }

    public function actionLogin()
    {
        $this->layout = "login";

        $model = new VendorLogin();
        if(!Yii::$app->user->isGuest){
            $this->redirect('index');
        }else{


            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $vendor_id=Yii::$app->user->getId();
                $package=Vendor::packageCheck($vendor_id);
                $status=Vendor::statusCheck($vendor_id);
                if(!$status){

                    $session = Yii::$app->session;
                    Yii::$app->user->logout();
                    $session->destroy();
                    Yii::$app->session->setFlash('danger', "Kindly contact admin account deactivated!");
                    Yii::warning('[Account Deactivated] Vendor needs contact admin account deactivated', __METHOD__);
                    return $this->redirect('login');
                }
                if($package){
                    Yii::info('[Vendor Login] Vendor Login successfully', __METHOD__);
                    return $this->redirect('index');
                }
                else
                {
                    $session = Yii::$app->session;
                    Yii::$app->user->logout();
                    $session->destroy();
                    Yii::$app->session->setFlash('danger', "Kindly contact admin package expired!");
                    Yii::warning('[Package Expired] Vendor needs contact admin package expired', __METHOD__);
                    return $this->redirect('login');
                }
            } else {

                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        Yii::$app->user->logout();
        $session->destroy();

        return $this->redirect('login');
    }

    public function actionDashboard()
    {

        $vendoritemcnt=Vendoritem::vendoritemcount();
        $monthitemcnt=Vendoritem::vendoritemmonthcount();
        $dateitemcnt=Vendoritem::vendoritemdatecount();
        $packageenddate=Vendor::getVendor('package_end_date');
        return $this->render('index', ['vendoritemcnt' => $vendoritemcnt,'monthitemcnt'=>$monthitemcnt,'dateitemcnt'=>$dateitemcnt,'packageenddate'=>$packageenddate]);
    }

    public function actionChangepassword()
    {
        $session = Yii::$app->session;
        $users_tbl = Vendor::find()->where(['vendor_contact_email' => Vendor::getVendor('vendor_contact_email')])->one();
        $model = new VendorPassword;
        $model->scenario = 'change';
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $form = Yii::$app->request->post('VendorPassword');


            if(!Yii::$app->getSecurity()->validatePassword($form['old_password'], $users_tbl['vendor_password']))
            {
                Yii::$app->session->setFlash('danger', "Old password does not match!");
                $this->redirect(['changepassword']);
                return false;

            }
            else if($form['new_password'] !== $form['confirm_password'])
            {
                echo Yii::$app->session->setFlash('danger', "Old password and new password does not match!");
                $this->redirect(['changepassword']);
                return false;
            }
            else
            {
                $users_tbl->scenario = 'change';
                $users_tbl->vendor_password = Yii::$app->getSecurity()->generatePasswordHash($form['new_password']);
                $users_tbl->save();
                echo Yii::$app->session->setFlash('success', "SuccessFully changed your password!");
                return $this->render('index');
            }
        }
        return $this->render('changepasswords', ['model'=> $model]);
    }

    public function actionRecoverypassword()
    {
        $model = new VendorPassword;
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $form = Yii::$app->request->post('VendorPassword');

            $query = new Query;
            $query->select('vendor_contact_email')->from('whitebook_vendor')->where(['vendor_contact_email' => $form['vendor_contact_email']]);
            $command = $query->createCommand();
            $rows = $command->queryAll();
            if(!empty($rows))
            {
                $length = 10;
                $randomString = substr(str_shuffle(md5(time())),0,$length);
                $password = Yii::$app->getSecurity()->generatePasswordHash($randomString);
                $subject = 'Password Recovery';
                $message = 'User id : '.$form['vendor_contact_email'] . ', Your New Password : '.$randomString;
                $body = '';
                Yii::$app->newcomponent->sendmail($form['vendor_contact_email'], $subject, $body, $message,'FORGOT-PASSWORD');
                $command = Yii::$app->db->createCommand('UPDATE whitebook_vendor SET vendor_password="'.$password.'" WHERE vendor_contact_email="'.$form['vendor_contact_email'].'"');
                $command->execute();
                 if($command)
                {
                    echo Yii::$app->session->setFlash('success', 'New password send to your registered email-id.');
                }
                return $this->redirect('recoverypassword');
            }
            else{
                echo Yii::$app->session->setFlash('danger', "Email id is not registered!");
                return $this->redirect('recoverypassword');
            }
        }
        return $this->renderPartial('passwords', ['model' => $model]);
    }

    public function actionProfile($id=false)
    {
        $siteinfo = Siteinfo::find()->all();
        $to = $siteinfo[0]['email_id']; // admin email

        $session = Yii::$app->session;
        //echo $session['email'];die;
        $model = Vendor::find()->where('vendor_contact_email = "'.$session['email'].'"')->one();

        $model->scenario = 'vendorprofile';
        $base = Yii::$app->basePath;
        $len = rand(1,1000);

        $v_category = Yii::$app->db->createCommand('select category_name FROM whitebook_category where category_id IN('.$model['category_id'].')')->queryAll();

        foreach ($v_category as $key => $value) {
            $vendor_categories[] = $value['category_name'];
        }

        // Current logo
        $exist_logo_image = $model['vendor_logo_path'];

        // Current Phone numbers
        $vendor_contact_number = explode(',',$model['vendor_contact_number']);

        if($model->load(Yii::$app->request->post())) {

            //print_r($_POST);die;

            $model->vendor_contact_number = implode(',',$_POST['Vendor']['vendor_contact_number']);

            $model->vendor_working_hours = $_POST['Vendor']['vendor_working_hours'].':'.$_POST['Vendor']['vendor_working_min'];

            $model->vendor_working_hours_to = $_POST['Vendor']['vendor_working_hours_to'].':'.$_POST['Vendor']['vendor_working_min_to'];

            $file = UploadedFile::getInstances($model, 'vendor_logo_path');
            if (!empty($file)) {
                foreach ($file as $files) {
                    $model->vendor_logo_path=$files->baseName . '_' . $len .'.' . $files->extension;
                    $k=$base.'/web/uploads/vendor_logo/' .$model->vendor_logo_path;
                    $files->saveAs($k);
                }
            }else {  $model->vendor_logo_path = $exist_logo_image;}

            if($model->save())
            {
                $v_name = $model['vendor_name'];
                Yii::info('[Vendor Profile Updated] Vendor updated profile information', __METHOD__);
                echo Yii::$app->session->setFlash('success', "Successfully updated your profile!");
                return $this->redirect(['dashboard']);
            } else {
                echo Yii::$app->session->setFlash('danger', "Something went wrong!");
                return $this->render('profile', ['model' => $model]);
            }
        }
        return $this->render('profile', ['model' => $model,'vendor_contact_number'=>$vendor_contact_number,'vendor_categories'=>$vendor_categories]);

    }
}
