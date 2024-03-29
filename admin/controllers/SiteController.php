<?php
namespace admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use yii\web\Session;
use admin\models\Admin;
use admin\models\Customer;
use admin\models\Vendor;
use admin\models\VendorItem;
use yii\web\UploadedFile;
use common\models\PasswordForm;
use admin\models\LoginForm;
use common\models\UploadForm;
use common\models\Booking;

/**
 * Site controller.
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'recoverypassword'],
                        'allow' => true,
                    ],
                    [
                        //'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   //'logout' => ['post'],
                ],
            ],
        ];
    }

    /* method to change 404 page design in case
     * user not logged in
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // change layout for error action
            if ($action->id=='error' && Yii::$app->user->isGuest)
                $this->layout ='error-layout';
            return true;
        } else {
            return false;
        }
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
        $vendoritemcnt = VendorItem::itemcount();
        $monthitemcnt = VendorItem::itemmonthcount();
        $dateitemcnt = VendorItem::itemdatecount();
        $vendorcnt = Vendor::vendorcount();
        $vendormonth = Vendor::vendormonthcount();
        $vendorday = Vendor::vendordatecount();
        $customercnt = Customer::customercount();
        $customermonth = Customer::customermonthcount();
        $customerday = Customer::customerdatecount();

        $bookingExpired = Booking::find()
            ->where([
                'booking_status' => Booking::STATUS_EXPIRED
            ])
            ->count('booking_id');    
            
        $bookingRejected = Booking::find()
            ->where([
                'booking_status' => Booking::STATUS_REJECTED
            ])
            ->count('booking_id');    

        $bookingAccepted = Booking::find()
            ->where([
                'booking_status' => Booking::STATUS_ACCEPTED
            ])
            ->count('booking_id');    

        $bookingPending = Booking::find()
            ->where([
                'booking_status' => Booking::STATUS_PENDING
            ])
            ->count('booking_id');    

        return $this->render('index', [
            'vendoritemcnt' => $vendoritemcnt, 
            'monthitemcnt' => $monthitemcnt, 
            'dateitemcnt' => $dateitemcnt,
            'vendorcnt' => $vendorcnt, 
            'vendormonth' => $vendormonth, 
            'vendorday' => $vendorday,
            'customercnt' => $customercnt, 
            'customermonth' => $customermonth, 
            'customerday' => $customerday,
            'bookingExpired' => $bookingExpired,
            'bookingRejected' => $bookingRejected,
            'bookingAccepted'=> $bookingAccepted,
            'bookingPending' => $bookingPending
        ]);
    }

    public function actionLogin()
    {
        $this->layout = "login";

        Yii::$app->session->setFlash('danger', '');
        $model = new LoginForm();
        if (!Yii::$app->user->isGuest) {
            $this->redirect(['site/index']);
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $this->redirect(['site/index']);
            } else {
                return $this->render('login', [
                'model' => $model,
            ]);
            }
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    // todo remove method after anaylising in case not in use
    public function actionRecoverypassword()
    {
        $model = new PasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $form = Yii::$app->request->post('PasswordForm');
            $rows= Admin::find()->select(['admin_email'])->where(['admin_email'=>$form['admin_email']])->asArray()->all();
            if (!empty($rows)) {
                $length = 10;
                $randomString = substr(str_shuffle(md5(time())), 0, $length);
                $password = Yii::$app->getSecurity()->generatePasswordHash($randomString);

                Yii::$app->mailer->compose([
                        "html" => "admin/password-reset"
                            ],[
                        "message" => $message,
                        "user" => 'Admin'
                    ])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($form['admin_email'])
                    ->setSubject('Admin Password Recovery')
                    ->send();
				$command=Admin::updateAll(['admin_password' => $password],['admin_email= '.$form['admin_email']]);
                if($command)
                {
                    Yii::$app->session->setFlash('success', 'New password send to your registered email-id.');
                }
                return $this->redirect('recoverypassword');
            } else {
                Yii::$app->session->setFlash('danger', 'email is not registered!');

                return $this->redirect('recoverypassword');
            }
        }

        return $this->renderPartial('passwords', ['model' => $model]);
    }

    public function actionChangepassword()
    {
        $model = new PasswordForm();
        $model->scenario = 'change';
        /*$model->validate();
        print_r($model->getErrors());die;*/
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
                $vendoritemcnt = VendorItem::itemcount();
                $monthitemcnt = VendorItem::itemmonthcount();
                $dateitemcnt = VendorItem::itemdatecount();
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


    public function actionProfile($id = false)
    {
        $query = Admin::find()->where('id = '.Yii::$app->user->getId())->one();
        $query->scenario = 'profile';
        if ($query->load(Yii::$app->request->post())) {
            if ($query->save()) {
                Yii::$app->session->setFlash('success', 'Successfully updated your profile!');
                $vendoritemcnt = VendorItem::itemcount();
                $monthitemcnt = VendorItem::itemmonthcount();
                $dateitemcnt = VendorItem::itemdatecount();
                $vendorcnt = Vendor::vendorcount();
                $vendorperiod = Vendor::vendorperiod();
                $vendormonth = Vendor::vendormonthcount();
                $vendorday = Vendor::vendordatecount();
                $customercnt = Customer::customercount();
                $customermonth = Customer::customermonthcount();
                $customerday = Customer::customerdatecount();

                return $this->render('index', ['vendoritemcnt' => $vendoritemcnt, 'monthitemcnt' => $monthitemcnt, 'dateitemcnt' => $dateitemcnt,
        'vendorcnt' => $vendorcnt, 'vendormonth' => $vendormonth, 'vendorday' => $vendorday,
        'customercnt' => $customercnt,'vendorperiod' => $vendorperiod,  'customermonth' => $customermonth, 'customerday' => $customerday, ]);
            } else {
                Yii::$app->session->setFlash('danger', 'Something went wrong!');

                return $this->render('profile', ['model' => $query]);
            }
        }

        return $this->render('profile', ['model' => $query]);
    }

    public function actionGalleryitem()
    {
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
