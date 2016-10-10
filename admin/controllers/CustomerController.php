<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use admin\models\Customer;
use common\models\City;
use common\models\Country;
use admin\models\Authitem;
use common\models\Location;
use admin\models\Addresstype;
use admin\models\AddressQuestion;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use admin\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use arturoliveira\ExcelView;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { 
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
                       'actions' => ['create', 'address', 'address_delete', 'questions', 'update', 'index', 'view', 'delete', 'block', 'export', 'newsletter'],
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
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionAddress_delete()
    {
        $access = Authitem::AuthitemCheck('4', '26');
        
        if (yii::$app->user->can($access)) {
          
          $address_id = yii::$app->request->post('address_id');

          CustomerAddressResponse::deleteAll('address_id = ' . $address_id);
          CustomerAddress::deleteAll('address_id = ' . $address_id);
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
		    $command=Customer::updateAll(['message_status' => 0],'customer_id= '.$id);

        $model1 = CustomerAddress::find()
          ->where('customer_id = :customer_id', [':customer_id' => $id])
          ->one();

        return $this->render('view', [
            'model' => $this->findModel($id), 
            'model1' => $model1
        ]);
    }

    /**
     * Displays all address
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionAddress($id)
    {
        $access = Authitem::AuthitemCheck('2', '26');

        if (!yii::$app->user->can($access)) {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            
            return $this->redirect(['site/index']);
        }

        if(Yii::$app->request->isPost) {

          $customer_address = new CustomerAddress();
          
          if ($customer_address->load(Yii::$app->request->post())) {
              
              $customer_address->customer_id = $id;

              if ($customer_address->save(false)) {
                  
                  $address_id = $customer_address->address_id;

                  //save customer address response 
                  $questions = Yii::$app->request->post('question');

                  foreach ($questions as $key => $value) {
                      $customer_address_response = new CustomerAddressResponse();
                      $customer_address_response->address_id = $address_id;
                      $customer_address_response->address_type_question_id = $key;
                      $customer_address_response->response_text = $value;
                      $customer_address_response->save();
                  }
              }
          }
        }

        $addresses = array();

        $result = CustomerAddress::find()
          ->select('whitebook_city.city_name, whitebook_location.location, whitebook_customer_address.*')
          ->leftJoin('whitebook_location', 'whitebook_location.id = whitebook_customer_address.area_id')
          ->leftJoin('whitebook_city', 'whitebook_city.city_id = whitebook_customer_address.city_id')
          ->where('customer_id = :customer_id', [':customer_id' => $id])
          ->asArray()
          ->all();

        foreach($result as $row) {

          $row['questions'] = CustomerAddressResponse::find()
            ->select('aq.question, whitebook_customer_address_response.*')
            ->innerJoin('whitebook_address_question aq', 'aq.ques_id = address_type_question_id')
            ->where('address_id = :address_id', [':address_id' => $row['address_id']])
            ->asArray()
            ->all();

          $addresses[] = $row;
        }

        $customer_address_modal = new CustomerAddress();
        $addresstype = Addresstype::loadAddress();
        $country = Country::loadcountry();

        return $this->render('address', [
            'model' => $this->findModel($id), 
            'addresses' => $addresses,
            'customer_address_modal' => $customer_address_modal,
            'addresstype' => $addresstype,
            'country' => $country
        ]);
    }

    /**
     * Updates address questions 
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionQuestions()
    {
        $address_type_id = Yii::$app->request->post('address_type_id');

        $questions = AddressQuestion::find()->where('address_type_id = '. $address_type_id)->all();

        return $this->renderPartial('questions', [
            'questions' => $questions
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
            $model->scenario = 'createAdmin';
            
            if ($model->load(Yii::$app->request->post())) {
                
                $model->customer_dateofbirth=Yii::$app->formatter->asDate($model->customer_dateofbirth, 'php:Y-m-d');

                $model->customer_password = Yii::$app->getSecurity()->generatePasswordHash($model->customer_password);

                if ($model->save(false)) {
                    
                    Yii::info('[Customer Created] Admin created customer '.$model->customer_name, __METHOD__);
                }

                Yii::$app->session->setFlash('success', 'Customer detail added successfully!');

                return $this->redirect(['index']);
            } else {
                
                return $this->render('create', [
                    'model' => $model, 
                ]);
            }

        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
            $model->scenario = 'createAdmin';
           
            if ($model->load(Yii::$app->request->post())) {

                $model->customer_dateofbirth = Yii::$app->formatter->asDate($model->customer_dateofbirth, 'php:Y-m-d');
               
                $model->customer_password = Yii::$app->getSecurity()->generatePasswordHash($model->customer_password);
               
                $model->save();
               
                Yii::$app->session->setFlash('success', 'Customer detail updated successfully!');

                Yii::info('[Customer Updated] Admin updated customer '.$model->customer_name.' information', __METHOD__);

                return $this->redirect(['index']);

            } else {
                               
                return $this->render('update', [
                  'model' => $model
                ]);

            }
        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
              
              if ($this->findModel($id)->delete()) {

                  Yii::$app->session->setFlash('success', 'Customer deleted successfully!');

                  return $this->redirect(['index']);
              }

          } else {
              
              Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
    
        $command=Customer::updateAll(['customer_status' => $status],'customer_id= '.$data['id']);
    
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
                        Yii::$app->mailer->compose([
                                "html" => "admin/newsletter"
                                    ], [
                                "message"=>$message
                            ])
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($mail)
                            ->setSubject('Newsletter from Whitebook')
                            ->send();
                    }
                }

                return $this->redirect(['index']);
            } else {
              return $this->render('news', [
                'model' => $model, 
                'customer_email' => $customer_email
              ]);
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }
}
