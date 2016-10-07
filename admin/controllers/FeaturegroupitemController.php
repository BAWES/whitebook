<?php

namespace admin\controllers;

use Yii;
use common\models\Featuregroup;
use common\models\Featuregroupitem;
use common\models\FeaturegroupitemSearch;
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
class FeaturegroupitemController extends Controller
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
                       'actions' => ['create', 'update', 'index', 'view', 'block', 'delete', 'loadcategory', 'loaditems', 'loadsubcategory', 'loaditems', 'loadvendoritems', 'sort_feature_group'],
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
            
            $searchModel = new FeaturegroupitemSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
            ]);

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionSort_feature_group()
    {
        $request = Yii::$app->request;

        $sort = $request->post('sort_val');
        $featured_id = $request->post('featured_id');
        
        $command=Featuregroupitem::updateAll(['featured_sort' => $sort], 'featured_id= '.$featured_id);
        
        if ($command) {
            Yii::$app->session->setFlash('success', 'Sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
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
        
            $group = Featuregroup::loadfeaturegroup();
            $category = Category::loadcategoryname();
            $subcategory = Subcategory::loadsubcategoryname();
            $vendoritem = Vendoritem::loadvendoritem();
            $themelist = Themes::loadthemename();
            $model = new Featuregroupitem();

            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                
                if (count($model->item_id) >= 2) {
                    echo $item_id = implode(',', $model->item_id);
                } else {
                    $item_id = $model->item_id[0];
                }
                
                $model->item_id = $item_id;
                
                $exists = Featuregroupitem::findOne(['item_id' => $item_id, 'trash' => 'Default']);
                
                if ($exists) {
                    Yii::$app->session->setFlash('danger', 'Feature group item already exists!');

                    return $this->redirect(['index']);
                }
                
                $model->featured_start_date = Yii::$app->formatter->asDate($model->featured_start_date, 'php:Y-m-d');
      			    
                $model->featured_end_date = Yii::$app->formatter->asDate($model->featured_end_date, 'php:Y-m-d');
                
                $max_sort = Featuregroupitem::find()
                  ->select('max(featured_sort) as sort')
          				->where(['parent_category_id' => null])
          				->andwhere(['trash' => 'default'])
          				->andwhere(['category_level' => '0'])
          				->one();

                $sort = ($max_sort[0]['sort'] + 1);
            
                $model->featured_sort = $sort;

                $model->save();
            
                Yii::$app->session->setFlash('success', 'Feature Froup item created successfully!');

                return $this->redirect(['index']);
            
            } else {
                return $this->render('create', [
                  'model' => $model, 
                  'group' => $group, 
                  'vendoritem' => $vendoritem, 
                  'category' => $category, 
                  'subcategory' => $subcategory
                ]);
            }

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
        
            $model = $this->findModel($id);
            $vendorname = Vendor::loadvendorname();
            $group = Featuregroup::loadfeaturegroup();
            $category = Category::loadcategoryname();
            $subcategory_id = Subcategory::loadsubcategoryname();
            $vendoritem = Vendoritem::loadsubcategoryvendoritem($model->subcategory_id);
            $themelist = Themes::loadthemename();

            $themeid = Vendoritemthemes ::getthemelist($model->item_id);

            $themid = Vendoritemthemes ::getthemeid($model->item_id);
            $themid = array('0' => $themid);
            $themid = $themid['0'];
            $featuregroupitem = Vendoritem ::groupvendoritem($model->category_id, $model->subcategory_id);
            
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
				
				        $model->featured_start_date = Yii::$app->formatter->asDate($model->featured_start_date, 'php:Y-m-d');
      			    $model->featured_end_date = Yii::$app->formatter->asDate($model->featured_end_date, 'php:Y-m-d');
      			
                if (count($model->item_id) >= 2) {
                    $item_id = implode(',', $model->item_id);
                } else {
                    $item_id = $model->item_id[0];
                }
            
                $model->item_id = $item_id;
                $model->save();
                Yii::$app->session->setFlash('success', 'Feature group item updated successfully!');

                return $this->redirect(['index']);
            
            } else {
            
                return $this->render('update', [
                  'model' => $model, 
                  'group' => $group, 
                  'vendoritem' => $vendoritem, 
                  'vendorname' => $vendorname,
                  'category' => $category, 
                  'subcategory_id' => $subcategory_id, 
                  'featuregroupitem' => $featuregroupitem
                ]);
            }

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
            
            Yii::$app->session->setFlash('success', 'Feature group item deleted successfully!');

            return $this->redirect(['index']);

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
        $category = Category::find()->select('category_id,category_name')
          ->where(['category_id' => $k])
          ->andwhere(['category_allow_sale' => 'yes'])
          ->andwhere(['!=', 'trash', 'Deleted'])
          ->andwhere(['parent_category_id' => null])
          ->all();
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
        $subcategory = Category::find()->select('category_id,category_name')
          ->where(['parent_category_id' => $data['id']])
          ->andwhere(['!=', 'category_allow_sale', 'no'])
          ->andwhere(['!=', 'trash', 'Deleted'])
          ->andwhere(['!=', 'parent_category_id', 'null'])->all();
        echo  '<option value="">Select...</option>';
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }
    // Load respective vendor load items from category - subcategory based on -->
    public function actionLoaditems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $vendoritem = Vendoritem::find()->select('item_id,item_name')
          ->where(['category_id' => $data['id2'], 'subcategory_id' => $data['id3'], 'item_for_sale' => 'Yes', 'item_status' => 'Active'])->all();
        $count = Vendoritem::find()->select('item_id,item_name')->where(['category_id' => $data['id2'],
          'subcategory_id' => $data['id3'], 'item_for_sale' => 'Yes', 'item_status' => 'Active', ])->count();
        if (($count < 20) && ($count > 0)) {
            echo '<input type="checkbox" onclick="checkall(this.checked);">Select all';
        } else {
            echo '<div class="admin" style="color:red">No Items Avilable</div>';
        }
        foreach ($vendoritem as $key => $val) {
            echo '<label><input type="checkbox" name="Featuregroupitem[item_id][]" class="checkbox_all" value="'.$val['item_id'].'">'.$val['item_name'].'</label>';
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
        if (($model = Featuregroupitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLoadvendoritems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $vendoritem = Vendoritem::find()->select('item_id,item_name')->where(['category_id' => $data['id2'], 'subcategory_id' => $data['id3']])->all();

        echo '<input type="checkbox" onclick="checkall(this.checked);">Select all';
        foreach ($vendoritem as $key => $val) {
            echo '<label><input type="checkbox" name="Vendoritemquestion[item_id][]" class="checkbox_all" value="'.$val['item_id'].'">'.$val['item_name'].'</label>';
        }
    }

    public function actionBlock()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        $command=Featuregroupitem::updateAll(['group_item_status' => $status],'featured_id= '.$data['id']);
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
