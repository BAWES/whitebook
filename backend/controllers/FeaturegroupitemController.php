<?php

namespace backend\controllers;

use Yii;
use common\models\Featuregroup;
use common\models\Featuregroupitem;
use common\models\FeaturegroupitemSearch;
use common\models\Vendoritem;
use common\models\Vendoritemthemes;
use common\models\Themes;
use common\models\Vendor;
use common\models\Category;
use common\models\SubCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
/**
 * FeaturegroupitemController implements the CRUD actions for Featuregroupitem model.
 */
class FeaturegroupitemController extends Controller
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
     * Lists all Featuregroupitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeaturegroupitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Featuregroupitem model.
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
     * Creates a new Featuregroupitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$vendorid=Yii::$app->user->identity->id;

		$group= Featuregroup::loadfeaturegroup();

		$vendor = Vendor::find()->select(['category_id'])
		->where(['vendor_id' => $vendorid])
		->andwhere(['vendor_status' => 'Active'])
		->one();

		$a = explode (',',$vendor['category_id']);

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

		$vendoritem = Vendoritem::loadvendoritem();

		$themelist = Themes::loadthemename();

        $model = new Featuregroupitem();

        $model1 = new Vendoritemthemes();

        if ($model->load(Yii::$app->request->post()) && $model->vendor_id == $vendorid && $model->validate()) {
			$model->vendor_id=$vendorid;
			$model->validate();
			$item_id = implode(",",$model->item_id);
			$theme_id = implode(",",$model->themelist);
			$model->item_id=$item_id;
			$model1->item_id=$item_id;
			$model1->vendor_id=$vendorid;
			$model1->theme_id=$theme_id;
			$model1->save();
			$model->featured_start_date = date('Y-m-d', strtotime($model->featured_start_date));
			$model->featured_end_date = date('Y-m-d', strtotime($model->featured_end_date));
			$model->vendor_id=$vendorid;
			
			$exists = Featuregroupitem::findOne(["item_id" => $item_id,"trash" => 'Default',"vendor_id"=>$vendorid]);

			if($exists){
				Yii::$app->session->setFlash('danger', "Feature group  item already exists!");
        		return $this->redirect(['index']);
			}

			$model->save();
			
			Yii::$app->session->setFlash('success', "Feature group item created successfully!");

            return $this->redirect(['index']);

        } else {
            return $this->render('create',[
                'model' => $model,
                'group' => $group,
                'category' => $category,
                'vendoritem' => $vendoritem,
                'subcategory' => $subcategory,
                'themelist' => $themelist
            ]);
        }
    }

    /**
     * Updates an existing Featuregroupitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$vendorid=Yii::$app->user->identity->id;
		
		$vendor = Vendor::find()->select('category_id')->where(['vendor_id' => $vendorid])->one();
		
		$group= Featuregroup::loadfeaturegroup();
		
		$a=explode (',',$vendor['category_id']);
		
		$category = Category::find()
		->select(['category_id','category_name'])
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
		
		$vendoritem= Vendoritem::loadvendoritem();
		
		$themelist= Themes::loadthemename();
        
        $model1=new Vendoritemthemes();
        
        $model = $this->findModel($id);
		
		$themeid=Vendoritemthemes::getthemelist($model->vendor_id,$model->item_id);
		
		$id = Vendoritemthemes::getthemeid($model->vendor_id,$model->item_id);
		$id = array('0'=>$id);
		$id = $id['0'];
        
        $featuregroupitem = Vendoritem::groupvendoritem($model->vendor_id,$model->category_id,$model->subcategory_id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		   
		    $model->featured_start_date = date('Y-m-d', strtotime($model->featured_start_date));
		    $model->featured_end_date = date('Y-m-d', strtotime($model->featured_end_date));

			if($model->item_id){
				$item_id=implode(",",$model->item_id);
			} else {
				$item_id=0;
			}

			if($model->themelist){
				$theme_id=implode(",",$model->themelist);
			} else {
				$theme_id=0;
			}

			$model->item_id=$item_id;
			$model1->item_id=$item_id;
			$model1->vendor_id=$model->vendor_id;
			$model1->theme_id=$theme_id;

			$exists = Featuregroupitem::findOne(["item_id" => $item_id,"trash" => 'Default',"vendor_id"=>$vendorid]);

			if($exists){
				Yii::$app->session->setFlash('danger', "Feature group  item already exists!");
        		return $this->redirect(['index']);
			}

			$model->save();
			
			$command = Vendoritemthemes::updateAll(['theme_id' => $theme_id,'item_id' => $item_id],['id= '.$id]);
			
			Yii::$app->session->setFlash('success', "Feature Froup item Updated successfully!");
            
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'group' => $group,
                'vendoritem' => $vendoritem,
                'category' => $category,
                'subcategory' => $subcategory,
                'themelist' => $themelist,
                'featuregroupitem' => $featuregroupitem,
                'themeid' => $themeid
            ]);
        }
    }

    /**
     * Deletes an existing Featuregroupitem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$command=Featuregroupitem::updateAll(['trash' => 'Deleted'],['featured_id= '.$id]);
		
		Yii::$app->session->setFlash('success', "Feature group item deleted successfully!");
        
        return $this->redirect(['index']);
    }

    public static function actionLoadcategory()
    {
		if(!Yii::$app->request->isAjax) 
			die();
			
		$data = Yii::$app->request->post();

		$categoryid = Vendor::find()->select('category_id')->where(['vendor_id' => 1])->one();
		
		$category = Category::find()->select('category_id,category_name')->where(['category_id' => $k])->all();
		
		echo  '<option value="">Select...</option>';
		
		foreach($category as $key=>$val)
		{
			echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
		}
	}

    public function actionLoadsubcategory()
    {
		if(Yii::$app->request->isAjax)
			die();

		$data = Yii::$app->request->post();
		
		$subcategory = Category::find()->select('category_id,category_name')->where(['parent_category_id' => $data['id']])->all();
		
		echo  '<option value="">Select...</option>';

		foreach($subcategory as $key=>$val)
		{
			echo '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
		}
	}

	public function actionLoaditems()
	{
		if(!Yii::$app->request->isAjax)
			die();

		$vendorid = Yii::$app->user->identity->id;
		$data = Yii::$app->request->post();
		
		$vendoritem = Vendoritem::find()->select('item_id,item_name')->where(['vendor_id' => $vendorid
		,'category_id' => $data['category_id'],'subcategory_id' => $data['subcategory_id']])->all();
		
		echo  '<option value="">Select...</option>';
		
		foreach($vendoritem as $key=>$val)
		{
			echo '<option value="'.$val['item_id'].'">'.$val['item_name'].'</option>';
		}
	}

    /**
     * Finds the Featuregroupitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Featuregroupitem the loaded model
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
}
