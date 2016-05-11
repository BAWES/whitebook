<?php

namespace admin\controllers;

use Yii;
use admin\models\Slide;
use admin\models\SlideSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\Authitem;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
* SlideController implements the CRUD actions for Slide model.
*/
class SlideController extends Controller
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
                        'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'sort_slide', 'status'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //        'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
    * Lists all Slide models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('1', '32');
        if (yii::$app->user->can($access)) {
            $searchModel = new SlideSearch();
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

    /**
    * Displays a single Slide model.
    *
    * @param int $id
    *
    * @return mixed
    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
    * Creates a new Slide model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @return mixed
    */
    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        $command=Slide::updateAll(['slide_status' => $status],'slide_id= '.$data['cid']);
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        }

        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }

    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '1');
        if (yii::$app->user->can($access)) {
            $model = new \admin\models\Slide();
            $model->scenario = "create";

            if ($model->load(Yii::$app->request->post())) {

                //Switch value from checkbox input into Active or Inactive strings
                $model->slide_status = $model->slide_status ? 'Active' : 'Deactive';

                //Get Maximum sort order, then increment by 1 for this upload
                
                $max_sort = Slide::find()->select('max(sort) as sort')
				->where(['trash' => 'default'])
				->asarray()
				->all();
                $model->sort = ($max_sort[0]['sort'] + 1);
                //Get Uploaded Instances
                $model->slide_video_url = UploadedFile::getInstance($model, 'slide_video_url');
                $model->slide_image = UploadedFile::getInstance($model, 'slide_image');

                if($model->save()){
                    Yii::$app->session->setFlash('success', 'Slide created successfully!');
                    return $this->redirect(['index']);
                }
            }

            return $this->render('create', [
                'model' => $model,
            ]);

        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Updates an existing Slide model.
    * If update is successful, the browser will be redirected to the 'view' page.
    *
    * @param int $id
    *
    * @return mixed
    */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '32');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                //Change value from checkbox input into Active or Inactive strings
                $model->slide_status = $model->slide_status ? 'Active' : 'Deactive';

                //If there's uploaded files, replace old ones
                if(UploadedFile::getInstance($model, 'slide_video_url') || UploadedFile::getInstance($model, 'slide_image')){
                    //Set scenario to Update
                    $model->scenario = "update";

                    //Store old values in case we need to delete them
                    $oldVideo = $model->slide_video_url;
                    $oldImage = $model->slide_image;

                    //Get Uploaded Instances
                    $model->slide_video_url = UploadedFile::getInstance($model, 'slide_video_url');
                    $model->slide_image = UploadedFile::getInstance($model, 'slide_image');

                    if ($model->save()) {
                        //Delete Old Uploads
                        Yii::$app->resourceManager->delete("slider_uploads/" . $oldVideo);
                        Yii::$app->resourceManager->delete("slider_uploads/" . $oldImage);

                        //Redirect
                        Yii::$app->session->setFlash('success', 'Slides updated successfully!');
                        return $this->redirect(['view', 'id' => $model->slide_id]);
                    }

                }else{//Otherwise, just change the data and redirect
                    if ($model->save()) { //ISSUE HERE!! MASSIVELY ASSIGNED BLANK IMAGE/VIDEO<MAYBE SCENARIO?
                        Yii::$app->session->setFlash('success', 'Slides updated successfully!');
                        return $this->redirect(['view', 'id' => $model->slide_id]);
                    }
                }

            }

            return $this->render('update', [
                'model' => $model,
            ]);

        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionStatus()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $ids = implode('","', $data['keylist']);
        if ($data['status'] == 'Delete') {
			$command=Slide::updateAll(['trash' => 'Deleted'],['IN','slide_id',$ids]);
            echo ($command) ? Yii::$app->session->setFlash('success', 'Slide deleted successfully!') : Yii::$app->session->setFlash('danger', 'Something went wrong');
        } else {
            $command=Slide::updateAll(['slide_status' => $data['status']],['IN','slide_id',$ids]);
            echo ($command) ? Yii::$app->session->setFlash('success', 'Slide status updated successfully!') : Yii::$app->session->setFlash('danger', 'Something went wrong');
        }
    }

    public function actionSort_slide()
    {
        $sort = $_POST['sort_val'];
        $slide_id = $_POST['slide_id'];
        $command=Slide::updateAll(['sort' => $sort],['IN','slide_id',$slide_id]);
        if ($command) {
            Yii::$app->session->setFlash('success', 'Slide sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

    /**
    * Deletes an existing Slide model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    *
    * @param int $id
    *
    * @return mixed
    */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('1', '32');
        if (yii::$app->user->can($access)) {

            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Finds the Slide model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    *
    * @param int $id
    *
    * @return Slide the loaded model
    *
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id)
    {
        if (($model = Slide::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
