<?php

namespace admin\controllers;

use Yii;
use common\models\Slide;
use common\models\SlideSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Authitem;
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
             $command = \Yii::$app->db->createCommand('UPDATE whitebook_slide SET slide_status="'.$status.'" WHERE slide_id='.$data['cid']);
             $command->execute();
             if ($status == 'Active') {
                 return \Yii::$app->params['appImageUrl'].'active.png';
             }

             return \Yii::$app->params['appImageUrl'].'inactive.png';
         }

    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '1');
        if (yii::$app->user->can($access)) {
            $model = new Slide();
            if ($model->load(Yii::$app->request->post())) {
                $model->slide_status = (Yii::$app->request->post()['Slide']['slide_status']) ? 'Active' : 'Deactive';
                $max_sort = $model->findBysql("SELECT MAX(`sort`) as sort FROM `whitebook_slide` where trash = 'Default'")->asArray()->all();
                $sort = ($max_sort[0]['sort'] + 1);
            // }
            $model->sort = $sort;
                $model->save(false);

                if ($model->slide_type == 'video') {
                    $file = UploadedFile::getInstances($model, 'slide_video_url');
                } else {
                    $file = UploadedFile::getInstances($model, 'slide_image');
                }
                $slide_id = $model->slide_id;
                $base = Yii::$app->basePath;
            //echo $file[2];die;
            /*
            echo '<pre>';
            print_r ($file);die;
            die;*/
            if ($file) {
                foreach ($file as $files) {
                    $img_ext = array('jpg', 'jpeg', 'png');
                    $ext = $files->extension;
                    if (in_array($ext, $img_ext)) {
                        $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$slide_id.'.png');
                    } elseif ($ext == 'mp4') {
                        $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$slide_id.'.mp4');
                    } elseif ($ext == 'avi') {
                        $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$slide_id.'.avi');
                    }
                }
            }
                echo Yii::$app->session->setFlash('success', 'Slides created successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model,
            ]);
            }
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
                $model->slide_status = (Yii::$app->request->post()['Slide']['slide_status']) ? 'Active' : 'Deactive';
                $model->save(false);
                $base = Yii::$app->basePath;
                if ($model->slide_type == 'video') {
                    $file = UploadedFile::getInstances($model, 'slide_video_url');
                } else {
                    $file = UploadedFile::getInstances($model, 'slide_image');
                }
                if ($file) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png')) {
                        unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png');
                    }
                    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.mp4')) {
                        unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.mp4');
                    }
                    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.avi')) {
                        unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.avi');
                    }
                }
                if ($file) {
                    foreach ($file as $files) {
                        $img_ext = array('jpg', 'jpeg', 'png');
                        $ext = $files->extension;
                        if (in_array($ext, $img_ext)) {
                            $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$id.'.png');
                        } elseif ($ext == 'mp4') {
                            $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$id.'.mp4');
                        } elseif ($ext == 'avi') {
                            $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$id.'.avi');
                        }
                    }
                }
                echo Yii::$app->session->setFlash('success', 'Slides updated successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                'model' => $model,
            ]);
            }
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
            $command = \Yii::$app->db->createCommand('UPDATE whitebook_slide SET trash="Deleted" WHERE slide_id IN("'.$ids.'")');
            $command->execute();
            echo ($command) ? Yii::$app->session->setFlash('success', 'Slide deleted successfully!') : Yii::$app->session->setFlash('danger', 'Something went wrong');
        } else {
            $command = \Yii::$app->db->createCommand('UPDATE whitebook_slide SET slide_status="'.$data['status'].'" WHERE slide_id IN("'.$ids.'")');
            $command->execute();
            echo ($command) ? Yii::$app->session->setFlash('success', 'Slide status updated successfully!') : Yii::$app->session->setFlash('danger', 'Something went wrong');
        }
    }

    public function actionSort_slide()
    {
        $sort = $_POST['sort_val'];
        $slide_id = $_POST['slide_id'];
        $command = \Yii::$app->DB->createCommand(
        'UPDATE whitebook_slide SET sort="'.$sort.'" WHERE slide_id='.$slide_id);
        if ($command->execute()) {
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
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png')) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png');
            }
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.mp4')) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.mp4');
            }
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.avi')) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.avi');
            }

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
