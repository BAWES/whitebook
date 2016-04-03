<?php

namespace admin\controllers;

use Yii;
use common\models\Vendoritemquestion;
use common\models\VendoritemquestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Category;
use common\models\SubCategory;
use common\models\Vendor;
use common\models\Vendoritem;

/**
 * VendoritemquestionController implements the CRUD actions for Vendoritemquestion model.
 */
class VendoritemquestionController extends Controller
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Vendoritemquestion models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendoritemquestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vendoritemquestion model.
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
     * Creates a new Vendoritemquestion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendoritemquestion();
        $category = Category::loadcategoryname();
        $subcategory = Subcategory::loadsubcategoryname();
        $vendorname = Vendor::loadvendorname();
        $vendoritem = Vendoritem::loadvendoritem();
        //var_dump($_POST);die;
        if ($model->load(Yii::$app->request->post())) {
            $model->item_id = implode(',', $model->item_id);
            $model->validate();
            $model->save();

            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model, 'category' => $category, 'subcategory' => $subcategory, 'vendorname' => $vendorname,
                'vendoritem' => $vendoritem,
            ]);
        }
    }

    /**
     * Updates an existing Vendoritemquestion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->item_id = explode(',', $model->item_id);
        $category = Category::loadcategoryname();
        $subcategory = Subcategory::loadsubcategoryname();
        $vendorname = Vendor::loadvendorname();
        $vendoritem = Vendoritem::loadvendoritem();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->item_id = implode(',', $model->item_id);
            $model->save();

            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model, 'category' => $category, 'subcategory' => $subcategory, 'vendorname' => $vendorname,
                'vendoritem' => $vendoritem,
            ]);
        }
    }

    /**
     * Deletes an existing Vendoritemquestion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Vendoritemquestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Vendoritemquestion the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoritemquestion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
