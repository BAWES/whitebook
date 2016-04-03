<?php

namespace admin\controllers;

use Yii;
use common\models\Banner;
use common\models\BannerSearch;
use common\models\Authitem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class BannerController extends Controller
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'sort_banner', 'status'],
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
     * Lists all Country models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('1', '1');
        if (yii::$app->user->can($access)) {
            $searchModel = new BannerSearch();
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

    public function actionSort_banner()
    {
        $sort = $_POST['sort_val'];
        $banner_id = $_POST['banner_id'];
        $command = \Yii::$app->DB->createCommand(
        'UPDATE whitebook_banner SET sort="'.$sort.'" WHERE banner_id='.$banner_id);

        if ($command->execute()) {
            Yii::$app->session->setFlash('success', 'Banner sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

    /**
     * Displays a single Country model.
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
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '32');
        if (yii::$app->user->can($access)) {
            $model = new Banner();
            $model->scenario = 'register';
            if ($model->load(Yii::$app->request->post())) {
                $model->banner_status = (Yii::$app->request->post()['Banner']['banner_status']) ? 'Active' : 'Deactive';
                $max_sort = $model->findBysql("SELECT MAX(`sort`) as sort FROM `whitebook_banner` where trash = 'Default'")->asArray()->all();
                $sort = ($max_sort[0]['sort'] + 1);
                $model->sort = $sort;
                $model->save(false);
                $banner_id = $model->banner_id;
                $base = Yii::$app->basePath;
                $file = UploadedFile::getInstances($model, 'banner_image');
                if ($file) {
                    foreach ($file as $files) {
                        $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$banner_id.'.png');
                    }
                }

                echo Yii::$app->session->setFlash('success', 'Banner created successfully!');

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
     * Updates an existing Country model.
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
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->banner_status = (Yii::$app->request->post()['Banner']['banner_status']) ? 'Active' : 'Deactive';
                $model->save();
                $base = Yii::$app->basePath;
                if ($model->banner_type == 2) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png')) {
                        unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png');
                    }
                } else {
                    $update_video_url = Yii::$app->db->createCommand('UPDATE whitebook_banner SET banner_video_url="" WHERE banner_id='.$id);
                    $update_video_url->execute();
                }
                $file = UploadedFile::getInstances($model, 'banner_image');
                if ($file) {
                    foreach ($file as $files) {
                        $files->saveAs($base.'/web/uploads/banner_images/'.'banner_'.$id.'.png');
                    }
                }
                echo Yii::$app->session->setFlash('success', 'Banner updated successfully!');

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

    /**
     * Deletes an existing Country model.
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
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png')) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/web/uploads/banner_images/banner_'.$id.'.png');
            }
            echo Yii::$app->session->setFlash('success', 'Banner deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }
    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Country the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
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
        $command = \Yii::$app->db->createCommand('UPDATE whitebook_banner SET banner_status="'.$status.'" WHERE banner_id='.$data['cid']);
        $command->execute();
        if ($status == 'Active') {
            return \Yii::$app->params['appImageUrl'].'active.png';
        }

        return \Yii::$app->params['appImageUrl'].'inactive.png';
    }

    /* Banner gridview status changes */
    public function actionStatus()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $ids = implode('","', $data['keylist']);
        if ($data['status'] == 'Delete') {
            $command = \Yii::$app->db->createCommand('UPDATE whitebook_banner SET trash="Deleted" WHERE banner_id IN("'.$ids.'")');
            $command->execute();
            echo ($command) ? Yii::$app->session->setFlash('success', 'Banner deleted successfully!') : Yii::$app->session->setFlash('danger', 'Something went wrong');
        } else {
            $command = \Yii::$app->db->createCommand('UPDATE whitebook_banner SET banner_status="'.$data['status'].'" WHERE banner_id IN("'.$ids.'")');
            $command->execute();
            echo ($command) ? Yii::$app->session->setFlash('success', 'Banner status updated successfully!') : Yii::$app->session->setFlash('danger', 'Something went wrong');
        }
    }
}
