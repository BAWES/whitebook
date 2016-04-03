<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use common\models\Customer;
use common\models\City;
use common\models\Country;
use common\models\Authitem;
use common\models\Location;
use common\models\Addresstype;
use common\models\CustomerAddress;
use common\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use arturoliveira\ExcelView;
use yii\helpers\Setdateformat;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'export', 'newsletter'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '26');
        if (yii::$app->user->can($access)) {
            $searchModel = new CustomerSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $count = $dataProvider->getTotalCount();

            return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $count,
        ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Displays a single Customer model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $command = \Yii::$app->DB->createCommand(
        'UPDATE `whitebook_customer` SET `message_status`="0" WHERE customer_id='.$id);
        $command->execute();

        $model1 = CustomerAddress::find()
        ->where('customer_id = :customer_id', [':customer_id' => $id])->one();

        return $this->render('view', [
            'model' => $this->findModel($id), 'model1' => $model1,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '26');
        if (yii::$app->user->can($access)) {
            $model = new Customer();
            $model1 = new CustomerAddress();
            if ($model->load(Yii::$app->request->post()) && $model1->load(Yii::$app->request->post()) && Model::validateMultiple([$model, $model1])) {
                $model->customer_dateofbirth = Setdateformat::convert($model->customer_dateofbirth);
                $model->customer_password = Yii::$app->getSecurity()->generatePasswordHash($model->customer_password);
                if ($model->save(false)) {
                    $model1->customer_id = $model->customer_id; // no need for validation rule on user_id as you set it yourself
            $model1->save();
                    Yii::info('Admin created customer '.$model->customer_name, __METHOD__);
                }
                echo Yii::$app->session->setFlash('success', 'Customer detail added successfully!');

                return $this->redirect(['index']);
            } else {
                $addresstype = Addresstype::loadAddresstype();
                $country = Country::loadcountry();

                return $this->render('create', [
                'model' => $model, 'model1' => $model1, 'addresstype' => $addresstype, 'country' => $country,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '26');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $model1 = CustomerAddress::find()
        ->where('customer_id = :customer_id', [':customer_id' => $id])->one();

            if ($model->load(Yii::$app->request->post()) && $model1->load(Yii::$app->request->post()) && Model::validateMultiple([$model, $model1])) {
                $model->customer_dateofbirth = Setdateformat::convert($model->customer_dateofbirth);
                $model->customer_password = Yii::$app->getSecurity()->generatePasswordHash($model->customer_password);
                $model->save();
                $model1->save();
                echo Yii::$app->session->setFlash('success', 'Customer detail updated successfully!');
                Yii::info('Admin updated customer '.$model->customer_name.' information', __METHOD__);

                return $this->redirect(['index']);
            } else {
                $addresstype = Addresstype::loadAddresstype();
                $country = Country::loadcountry();
                $city = City::loadcity();
                $location = Location::loadlocation();

                return $this->render('update', [
                'model' => $model, 'model1' => $model1, 'addresstype' => $addresstype, 'country' => $country, 'location' => $location, 'city' => $city,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

      /**
       * Deletes an existing Customer model.
       * If deletion is successful, the browser will be redirected to the 'index' page.
       *
       * @param string $id
       *
       * @return mixed
       */
      public function actionDelete($id)
      {
          $access = Authitem::AuthitemCheck('3', '26');
          if (yii::$app->user->can($access)) {
              $model = $this->findModel($id);
              $sql = 'DELETE from  whitebook_customer WHERE customer_id='.$id;
              $command = \Yii::$app->DB->createCommand($sql);
              if ($command->execute()) {
                  echo Yii::$app->session->setFlash('success', 'Customer deleted successfully!');

                  return $this->redirect(['index']);
              }
          } else {
              echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

              return $this->redirect(['site/index']);
          }
      }
    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Customer the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
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
        $command = \Yii::$app->db->createCommand('UPDATE whitebook_customer SET customer_status="'.$status.'" WHERE customer_id='.$data['id']);
        $command->execute();
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionExport()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ExcelView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filename' => 'Customer list',
            'fullExportType' => 'xls', //can change to html,xls,csv and so on
            'grid_mode' => 'export',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'customer_name',
                'customer_email',
                'customer_gender',
                'customer_mobile',
                'customer_dateofbirth',
                'customer_gender',
              ],
        ]);
    }

    public function actionNewsletter()
    {
        $access = Authitem::AuthitemCheck('1', '26');
        if (yii::$app->user->can($access)) {
            $model = new Customer();
            $customer_email = Customer::find()
            ->select(['customer_email'])
            ->where(['customer_status' => 'Active'])
            ->andwhere(['trash' => 'default'])
            ->all();
            $customer_email = ArrayHelper::map($customer_email, 'customer_email', 'customer_email');
            $model1 = new CustomerAddress();
            $model->scenario = 'newsletter';
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                $k = array();
                $k = $model->newsmail;
                foreach ($k as $mail) {
                    if ($model->content) {
                        echo $mail;
                        $subject = 'News letter from Whitebook';
                        $message = 'News letter send Successfully';
                        $body = $model->content;
                        Yii::$app->newcomponent->sendmail($mail, $subject, $body, $message);
                    }
                }

                return $this->redirect(['index']);
            } else {
                return $this->render('news', [
                'model' => $model, 'customer_email' => $customer_email,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }
}
