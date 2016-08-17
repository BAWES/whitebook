<?php
namespace admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use yii\web\Session;
use admin\models\Admin;
use admin\models\Customer;
use admin\models\Vendor;
use admin\models\Vendoritem;
use yii\web\UploadedFile;
use common\models\PasswordForm;
use common\models\LoginForm;
use common\models\UploadForm;

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
                'class' => AccessControl::className(),
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
                    echo Yii::$app->session->setFlash('success', 'New password send to your registered email-id.');
                }
                return $this->redirect('recoverypassword');
            } else {
                echo Yii::$app->session->setFlash('danger', 'email is not registered!');

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


    public function actionProfile($id = false)
    {
        $query = Admin::find()->where('id = '.Yii::$app->user->getId())->one();
        $query->scenario = 'profile';
        if ($query->load(Yii::$app->request->post())) {
            if ($query->save()) {
                echo Yii::$app->session->setFlash('success', 'Successfully updated your profile!');
                $vendoritemcnt = Vendoritem::itemcount();
                $monthitemcnt = Vendoritem::itemmonthcount();
                $dateitemcnt = Vendoritem::itemdatecount();
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
                echo Yii::$app->session->setFlash('danger', 'Something went wrong!');

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
