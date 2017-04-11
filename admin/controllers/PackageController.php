<?php

namespace admin\controllers;

use Yii;
use common\models\Package;
use common\models\PackageSearch;
use common\models\VendorItem;
use common\models\VendorItemToPackage;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

/**
 * PackageController implements the CRUD actions for Package model.
 */
class PackageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Package models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateItem($id)
    {
        $items = Yii::$app->request->post('items');

        $arr_items = [];

        if(empty($items)) {
            $items = [];
        }

        foreach ($items as $value) {

            $item_to_package = VendorItemToPackage::find()
                ->where([
                    'item_id' => $value,
                    'package_id' => $id
                ])
                ->one();

            if(!$item_to_package) {
                $item_to_package = new VendorItemToPackage();
                $item_to_package->item_id = $value;
                $item_to_package->package_id = $id;
                $item_to_package->save();
            }            

            $arr_items[] = $value;
        }

        if($arr_items) {
            VendorItemToPackage::deleteAll('package_id = ' . $id . ' AND 
                item_id NOT IN ('.implode(',', $arr_items).')');     
        }  
    }

    /**
     * Displays a single Package model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $items = VendorItem::find()
            ->defaultItems()
            ->all();

        $selected_items = VendorItem::find()
            ->joinPackage()
            ->defaultItems()
            ->package($id)
            ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $items,
            'selected_items' => $selected_items
        ]);
    }

    /**
     * Creates a new Package model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Package();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if($imageFile) 
            {
                $image_name = Yii::$app->security->generateRandomString();

                $model->package_background_image = Package::UPLOAD_FOLDER . $image_name . '.' . $imageFile->extension;

                Yii::$app->resourceManager->save(
                    $imageFile, //file upload object  
                    $model->package_background_image
                );
            }

            $model->save();

            return $this->redirect(['view', 'id' => $model->package_id]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Package model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if($imageFile) {
                $image_name = Yii::$app->security->generateRandomString();

                $model->package_background_image = Package::UPLOAD_FOLDER . $image_name . '.' . $imageFile->extension;

                Yii::$app->resourceManager->save(
                    $imageFile, //file upload object  
                    $model->package_background_image
                );
            }
            
            $model->save();

            return $this->redirect(['index', 'id' => $model->package_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Package model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Package model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Package the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Package::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
