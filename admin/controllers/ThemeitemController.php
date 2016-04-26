<?php

namespace admin\controllers;

use Yii;
use common\models\Featuregroup;
use common\models\Featuregroupitem;
use common\models\VendoritemthemesSearch;
use common\models\Vendoritem;
use common\models\Authitem;
use common\models\Vendoritemthemes;
use common\models\Themes;
use common\models\Vendor;
use common\models\Category;
use common\models\SubCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FeaturegroupitemController implements the CRUD actions for Featuregroupitem model.
 */
class ThemeitemController extends Controller
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'loadcategory', 'loaditems', 'loadsubcategory'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                 //   'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Featuregroupitem models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('4', '18');
        if (yii::$app->user->can($access)) {
            $searchModel = new VendoritemthemesSearch();
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
     * Displays a single Featuregroupitem model.
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
     * Creates a new Featuregroupitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '18');
        if (yii::$app->user->can($access)) {
            $category_id = Category::loadcategoryname();
            $subcategory_id = Subcategory::loadsubcategoryname();
            $vendoritem = Vendoritem::loadvendoritem();
            $themelist = Themes::loadthemename();
            $model = new Vendoritemthemes();
            $model1 = new Vendoritemthemes();
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                $item_id = implode(',', $model->item_id);
                echo $model->item_id = $item_id;
      			$model->theme_start_date = Yii::$app->formatter->asDate($model->theme_start_date, 'php:Y-m-d');
      			$model->theme_end_date = Yii::$app->formatter->asDate($model->theme_end_date, 'php:Y-m-d');
                $model->save();
                echo Yii::$app->session->setFlash('success', 'Theme group item created successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model, 'vendoritem' => $vendoritem, 'category_id' => $category_id, 'subcategory_id' => $subcategory_id, 'themelist' => $themelist,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
     * Updates an existing Featuregroupitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '18');
        if (yii::$app->user->can($access)) {
            $vendorname = Vendor::loadvendorname();
            $group = Featuregroup::loadfeaturegroup();
            $category_id = Category::loadcategoryname();
            $subcategory_id = Subcategory::loadsubcategoryname();
            $model = $this->findModel($id);
            $vendoritem = Vendoritem::loadsubcategoryvendoritem($model->subcategory_id);
            $themelist = Themes::loadthemename();
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
				
				$model->theme_start_date = Yii::$app->formatter->asDate($model->theme_start_date, 'php:Y-m-d');
      			$model->theme_end_date = Yii::$app->formatter->asDate($model->theme_end_date, 'php:Y-m-d');
                if ($model->item_id) {
                    $item_id = implode(',', $model->item_id);
                } else {
                    $item_id = 0;
                }

                $model->item_id = $item_id;
                $model->save();
                echo Yii::$app->session->setFlash('success', 'Theme item updated successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                'model' => $model, 'vendoritem' => $vendoritem,
                'category_id' => $category_id, 'subcategory_id' => $subcategory_id, 'themelist' => $themelist,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Deletes an existing Featuregroupitem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '18');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();
            echo Yii::$app->session->setFlash('success', 'Feature group item deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public static function actionLoadcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $categoryid = Vendor::find()->select('category_id')
          ->where(['vendor_id' => $data['id']])
          ->andwhere(['vendor_status' => 'Active'])
          ->one();
        $k = explode(',', $categoryid['category_id']);
        $category = Category::find()->select('category_id,category_name')->where(['category_id' => $k, 'category_allow_sale' => 'Yes', 'trash' => 'Default'])->all();
        echo  '<option value="">Select...</option>';
        foreach ($category as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }
    public function actionLoadsubcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $subcategory = Category::find()->select('category_id,category_name')->where(['parent_category_id' => $data['id'], 'category_allow_sale' => 'Yes', 'trash' => 'Default'])->all();
        echo  '<option value="">Select...</option>';
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }

    public function actionLoaditems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        $vendoritem = Vendoritem::find()->select('item_id,item_name')->where(['category_id' => $data['id2'], 'subcategory_id' => $data['id3'], 'item_for_sale' => 'Yes', 'item_status' => 'Active'])->all();
        $count = Vendoritem::find()->select('item_id,item_name')->where(['category_id' => $data['id2'], 'subcategory_id' => $data['id3'], 'item_for_sale' => 'Yes', 'item_status' => 'Active'])->count();
        if (($count < 20) && ($count > 0)) {
            echo '<input type="checkbox" onclick="checkall(this.checked);">Select all';
        } else {
            echo '<div class="admin" style="color:red">No Items Avilable</div>';
        }
        foreach ($vendoritem as $key => $val) {
            echo '<label><input type="checkbox" name="Vendoritemthemes[item_id][]" class="checkbox_all" value="'.$val['item_id'].'">'.$val['item_name'].'</label>';
            //echo  '<option value="'.$val['item_id'].'">'.$val['item_name'].'</option>';
        }
    }

    /**
     * Finds the Featuregroupitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Featuregroupitem the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoritemthemes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
