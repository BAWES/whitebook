<?php

namespace backend\controllers;
use Yii;
use common\models\Priorityitem;
use common\models\Vendoritem;
use common\models\Vendor;
use common\models\Category;
use common\models\SubCategory;
use common\models\PriorityitemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;


/**
 * PriorityitemController implements the CRUD actions for Priorityitem model.
 */
class PriorityitemController extends Controller
{
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
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Priorityitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PriorityitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Priorityitem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Priorityitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$vendorid=Yii::$app->user->identity->id;

        $model = new Priorityitem();

        $vendor = Vendor::find()->select('category_id')->where(['vendor_id' => $vendorid])->one();

		$a=explode (',',$vendor['category_id']);

		$category = Category::find()
		->where(['category_id' => $a])
		->andwhere(['category_allow_sale' => 'yes'])
		->andwhere(['!=', 'trash', 'Deleted'])
		->all();
		$category=ArrayHelper::map($category,'category_id','category_name');

		$subcategory = Subcategory::find()
		->where(['parent_category_id' => $a])
		->andwhere(['category_allow_sale' => 'yes'])
		->andwhere(['!=', 'trash', 'Deleted'])
		->all();
		$subcategory=ArrayHelper::map($subcategory,'category_id','category_name');

		$priorityitem= Vendoritem::loadvendoritem();

		if ($model->load(Yii::$app->request->post()) && $model->vendor_id == $vendorid && $model->validate()) {
			$item_id = implode(",",$model->item_id);
			$model->item_id = $item_id;
			$model->priority_start_date = date('Y-m-d', strtotime($model->priority_start_date));
			$model->priority_end_date = date('Y-m-d', strtotime($model->priority_end_date));
			$model->save();

            Yii::$app->session->setFlash('success', "Priority item added successfully!");
            
            return $this->redirect(['index']);
        } else {
            return $this->render('create',[
                'model' => $model,'priorityitem'=>$priorityitem,'category'=>$category,'subcategory'=>$subcategory,
            ]);
        }
    }

    /**
     * Updates an existing Priorityitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$vendorid=Yii::$app->user->identity->id;
		
        $vendorname= Vendor::loadvendorname();
        
        $vendor = Vendor::find()->select('category_id')->where(['vendor_id' => $vendorid])->one();
		
        $a=explode (',',$vendor['category_id']);
		$category = Category::find()
    		->where(['category_id' => $a])
    		->andwhere(['category_allow_sale' => 'yes'])
    		->andwhere(['!=', 'trash', 'Deleted'])
    		->all();
		$category = ArrayHelper::map($category,'category_id','category_name');

		$subcategory = Subcategory::find()
    		->where(['parent_category_id' => $a])
    		->andwhere(['category_allow_sale' => 'yes'])
    		->andwhere(['!=', 'trash', 'Deleted'])
    		->all();
		$subcategory = ArrayHelper::map($subcategory,'category_id','category_name');

		$vendoritem= Vendoritem::loadvendoritem();

        $priorityitem = Vendoritem ::groupvendoritem($model->vendor_id,$model->category_id,$model->subcategory_id);

        if ($model->load(Yii::$app->request->post()) &&($model->validate())) {
		
        	if($model->item_id){
    			$item_id=implode(",",$model->item_id);
    			$model->item_id=$item_id; 
            }

			$model->priority_start_date = date('Y-m-d', strtotime($model->priority_start_date));
            $model->priority_end_date = date('Y-m-d', strtotime($model->priority_end_date));
			$model->save();
		    
            Yii::$app->session->setFlash('success', "Priority item updated successfully!");
        
            return $this->redirect(['index']);

        } else {
            return $this->render('update', [
                'model' => $model,
                'vendoritem' => $vendoritem,
                'vendorname' => $vendorname,
                'category' => $category,
                'subcategory' => $subcategory,
                'priorityitem' => $priorityitem
            ]);
        }
    }

    /**
     * Deletes an existing Priorityitem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$model->trash = 'Deleted';
		$model->load(Yii::$app->request->post());
		$model->save();
		
        Yii::$app->session->setFlash('success', "Priority item deleted successfully!");
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Priorityitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Priorityitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Priorityitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
        
    public static function actionLoadcategory()
    {
		if(!Yii::$app->request->isAjax)
            die();

		$data = Yii::$app->request->post();
		
        $categoryid = Vendor::find()->select('category_id')->where(['vendor_id' => $data['id']])->one();
		
		$k = explode (',',$categoryid['category_id']);
		
        $category = Category::find()->select('category_id,category_name')->where(['category_id' => $k])->all();
		  
        echo  '<option value="">Select...</option>';
		
        foreach($category as $key=>$val) {
			echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
		}
	}

    public function actionLoadsubcategory()
    {
        if(!Yii::$app->request->isAjax)
            die();

        $data = Yii::$app->request->post();
        
        $subcategory = Category::find()->select('category_id,category_name')->where(['parent_category_id' => $data['id']])->all();
        
        echo  '<option value="">Select...</option>';
        
        foreach($subcategory as $key=>$val)
        {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
	}

    public function actionLoaditems()
    {
        if(!Yii::$app->request->isAjax)
            die();

        $vendorid = Yii::$app->user->identity->id;
        
        $data = Yii::$app->request->post();
        
        $vendoritem = Vendoritem::find()->select('item_id,item_name')->where([
            'vendor_id' => $vendorid,
            'category_id' => $data['category_id'],
            'subcategory_id' => $data['subcategory_id']
        ])->all();
        
        echo  '<option value="">Select...</option>';
        
        foreach($vendoritem as $key=>$val)
        {
            echo  '<option value="'.$val['item_id'].'">'.$val['item_name'].'</option>';
        }
	}

    public function actionLoadchildcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        $subcategory = Category::find()->select('category_id,category_name')
          ->where(['parent_category_id' => $data['id']])
          ->andwhere(['category_level' => 2])
          ->andwhere(['!=', 'category_allow_sale', 'no'])
          ->andwhere(['!=', 'trash', 'Deleted'])->all();
            
        echo  '<option value="">Select child category...</option>';
        
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }
}
