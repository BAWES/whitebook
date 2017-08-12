<?php

namespace admin\controllers;

use common\models\FeatureGroupItem;
use Yii;
use common\models\Vendor;
use admin\models\FeatureGroup;
use common\models\BlockedDate;
use admin\models\FeatureGroupSearch;
use yii\web\Controller;
use admin\models\AuthItem;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use admin\models\VendorItem;
use admin\models\FeatureGroupItemSearch;

/**
 * FeaturegroupController implements the CRUD actions for FeatureGroup model.
 */
class FeatureGroupController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { 
            $url = Yii::$app->urlManager->createUrl(['admin/site/login']);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                //    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => AccessControlList::can()
                    ],
                ],
            ],            
        ];
    }


    /**
     * Lists all FeatureGroup models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeatureGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Item in model 
     *
     * @return mixed
     */
    public function actionItems($id)
    {
        $searchModel = new FeatureGroupItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('items', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FeatureGroup model.
     *
     * @param string $id
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
     * Creates a new FeatureGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FeatureGroup();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->group_name = strtolower($model->group_name);
            $model->save();
            Yii::$app->session->setFlash('success', 'Feature group added successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
            'model' => $model,
        ]);
        }
    }

    /**
     * Updates an existing FeatureGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->group_name = strtolower($model->group_name);
            $model->save();
            Yii::$app->session->setFlash('success', 'Feature group updated successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
            'model' => $model,
        ]);
        }
    }

    /**
     * Deletes an existing FeatureGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        FeatureGroupItem::deleteAll(['group_id'=>$id]); // delete all products

        Yii::$app->session->setFlash('success', 'Feature group deleted successfully!');

        return $this->redirect(['index']);
    }

    /**
     * Finds the FeatureGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return FeatureGroup the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeatureGroup::findOne($id)) !== null) {
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
        
        $command = FeatureGroup::updateAll(['group_status' => $status],'group_id= '.$data['id']);
        
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionAssign($id){

        $model = FeatureGroup::findOne($id);

        if (!$model) {
            Yii::$app->session->setFlash('danger', 'Invalid Feature group');
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isPost) {

            FeatureGroupItem::deleteAll(['group_id' => $id]);

            $items = Yii::$app->request->post('items');
            
            foreach($items as $item) {

                $fgi = new FeatureGroupItem;
                $fgi->group_id = $id;
                $fgi->vendor_id = VendorItem::findOne($item)->vendor_id;
                $fgi->item_id = $item;
                $fgi->group_item_status = 'Active';
                $fgi->trash = 'Default';
                $fgi->save();
            }
            
            Yii::$app->session->setFlash('success','Feature group item changed.');

            return $this->redirect(['index']);
        }

        return $this->render('assign', ['model' => $model]);
    }
}
