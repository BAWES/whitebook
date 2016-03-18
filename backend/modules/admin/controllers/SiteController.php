<?php

namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\web\Session;
use backend\models\Customer;
use backend\models\Vendor;
use backend\models\Vendoritem;
use yii\web\UploadedFile;
use backend\models\PasswordForm;
use backend\models\LoginForm;
use backend\models\Admin;
use backend\models\UploadForm;

/**
 * Site controller.
 */
class SiteController extends Controller
{    
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
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            $this->redirect('login');
        }        
        Yii::$app->newcomponent->activity('Admin', 'Login successfully');
        $vendoritemcnt = Vendoritem::itemcount();
        $monthitemcnt = Vendoritem::itemmonthcount();
        $dateitemcnt = Vendoritem::itemdatecount();
        $vendorcnt = Vendor::vendorcount();
        $vendormonth = Vendor::vendormonthcount();
        $vendorday = Vendor::vendordatecount();
        $vendorperiod = Vendor::vendorperiod();
        $customercnt = Customer::customercount();
        $customermonth = Customer::customermonthcount();
        $customerday = Customer::customerdatecount();

        return $this->render('index', ['vendoritemcnt' => $vendoritemcnt, 'monthitemcnt' => $monthitemcnt, 'dateitemcnt' => $dateitemcnt,
        'vendorcnt' => $vendorcnt, 'vendormonth' => $vendormonth, 'vendorday' => $vendorday,
        'customercnt' => $customercnt, 'customermonth' => $customermonth, 'customerday' => $customerday, 'vendorperiod' => $vendorperiod, ]);
    }

    public function actionLogin()
    {
        Yii::$app->session->setFlash('danger', '');
        $model = new LoginForm();
        if (!Yii::$app->user->isGuest) {
            $this->redirect('index');
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $this->redirect('index');
            } else {
                return $this->renderPartial('login', [
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

    public function actionRecoverypassword()
    {
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            $this->redirect('login');
        }

        $model = new PasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $form = Yii::$app->request->post('PasswordForm');
            $query = new Query();
            $query->select('admin_email')->from('whitebook_admin')->where(['admin_email' => $form['admin_email']]);
            $command = $query->createCommand();
            $rows = $command->queryAll();
            if (!empty($rows)) {
                $length = 10;
                $randomString = substr(str_shuffle(md5(time())), 0, $length);
                $password = Yii::$app->getSecurity()->generatePasswordHash($randomString);
                $subject = 'Password Recovery';
                $body = 'User id : '.$form['admin_email'].', Your New Password : '.$randomString;
                $message = 'Check your email. New password send to your email id.';
                Yii::$app->newcomponent->sendmail($form['admin_email'], $subject, $body, $message);
                $command = Yii::$app->db->createCommand('UPDATE whitebook_admin SET admin_password="'.$password.'" WHERE admin_email="'.$form['admin_email'].'"');
                $command->execute();

                return $this->redirect('recoverypassword');
            } else {
                echo Yii::$app->session->setFlash('danger', 'Email id is not registered!');

                return $this->redirect('recoverypassword');
            }
        }

        return $this->renderPartial('passwords', ['model' => $model]);
    }
    public function actionChangepassword()
    {
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            $this->redirect('login');
        }

        $model = new PasswordForm();
        $model->scenario = 'change';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $form = Yii::$app->request->post('PasswordForm');
            $users_tbl = Admin::find()->where(['id' => Yii::$app->user->getId()])->one();

            //$user = Users::validatePassword($form['old_password']);

            if (!Yii::$app->getSecurity()->validatePassword($form['old_password'], $users_tbl['admin_password'])) {
                Yii::$app->session->setFlash('danger', 'Old password does not match!');
                $this->redirect(['changepassword']);

                return false;
            } elseif ($form['new_password'] !== $form['confirm_password']) {
                Yii::$app->session->setFlash('danger', 'Old password and new password does not match!');
                $this->redirect(['changepassword']);

                return false;
            } else {
                $users_tbl->scenario = 'change';
                $users_tbl->admin_password = Yii::$app->getSecurity()->generatePasswordHash($form['new_password']);
                $users_tbl->save();
                Yii::$app->session->setFlash('success', 'Successfully changed your password!');
                $vendoritemcnt = Vendoritem::itemcount();
                $monthitemcnt = Vendoritem::itemmonthcount();
                $dateitemcnt = Vendoritem::itemdatecount();
                $vendorcnt = Vendor::vendorcount();
                $vendormonth = Vendor::vendormonthcount();
                $vendorday = Vendor::vendordatecount();
                $customercnt = Customer::customercount();
                $customermonth = Customer::customermonthcount();
                $customerday = Customer::customerdatecount();

                return $this->render('index', ['vendoritemcnt' => $vendoritemcnt, 'monthitemcnt' => $monthitemcnt, 'dateitemcnt' => $dateitemcnt,
                'vendorcnt' => $vendorcnt, 'vendormonth' => $vendormonth, 'vendorday' => $vendorday,
                'customercnt' => $customercnt, 'customermonth' => $customermonth, 'customerday' => $customerday, ]);
            }
        }

        return $this->render('changepasswords', ['model' => $model]);
    }

    /*
     *  Cron Job  for if Vendor package expire with in two days
     */
    public function actionProfile($id = false)
    {
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            $this->redirect('login');
        }

        $query = Admin::find()->where('id = '.Yii::$app->user->getId())->one();
        $query->scenario = 'profile';
        if ($query->load(Yii::$app->request->post())) {
            if ($query->save()) {
                echo Yii::$app->session->setFlash('success', 'Successfully updated your profile!');
                $vendoritemcnt = Vendoritem::itemcount();
                $monthitemcnt = Vendoritem::itemmonthcount();
                $dateitemcnt = Vendoritem::itemdatecount();
                $vendorcnt = Vendor::vendorcount();
                $vendormonth = Vendor::vendormonthcount();
                $vendorday = Vendor::vendordatecount();
                $customercnt = Customer::customercount();
                $customermonth = Customer::customermonthcount();
                $customerday = Customer::customerdatecount();

                return $this->render('index', ['vendoritemcnt' => $vendoritemcnt, 'monthitemcnt' => $monthitemcnt, 'dateitemcnt' => $dateitemcnt,
        'vendorcnt' => $vendorcnt, 'vendormonth' => $vendormonth, 'vendorday' => $vendorday,
        'customercnt' => $customercnt, 'customermonth' => $customermonth, 'customerday' => $customerday, ]);
            } else {
                echo Yii::$app->session->setFlash('danger', 'Something went wrong!');

                return $this->render('profile', ['model' => $query]);
            }
        }

        return $this->render('profile', ['model' => $query]);
    }

    /*
     *  Cron Job  for if Vendor package expire with in two days
     */
    public function actionCron()
    {
        $model = Yii::$app->db->createCommand('SELECT vendor_id, vendor_contact_email, vendor_password, vendor_contact_email, vendor_end_date from whitebook_vendor
												where vendor_status="Active" and expire_notification = 0 and
												vendor_end_date = DATE_ADD(CURDATE(), INTERVAL 2 DAY)');
        $vendor = $model->queryAll();
        foreach ($vendor as $data => $vendor_data) {
            $command = Yii::$app->db->createCommand('UPDATE whitebook_vendor SET expire_notification=1 WHERE vendor_id='.$vendor_data['vendor_id']);
            $command->execute();

            $send = Yii::$app->mailer->compose()
                     ->setFrom('a.mariyappan88@gmail.com')
                     ->setTo($vendor_data['vendor_contact_email'])
                     ->setSubject('Welcome to Whitebook')
                     ->setTextBody('Your username : '.$vendor_data['vendor_contact_email'])
                     ->send();
        }
    }

    public function actionGalleryitem()
    {
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            $this->redirect('login');
        }

        $model = new UploadForm();
        $base = Yii::$app->basePath;
        $len = rand(1, 1000);

        if (Yii::$app->request->isPost) {
            $files = UploadedFile::getInstances($model, 'file');
            if ($files && $model->validate()) {
                $image = Yii::$app->image->load($files);
                $image->resize(25, 25);
                $image->saveAs($base.'/web/uploads/'.$image->baseName.'_'.$len.'.'.$image->extension);
            }
        }

        return $this->render('galleryitem', ['model' => $model]);
    }
}
