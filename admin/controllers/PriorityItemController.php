<?php

namespace admin\controllers;

use Yii;
use admin\models\PriorityItem;
use admin\models\PriorityitemSearch;
use admin\models\VendorItem;
use admin\models\Vendor;
use admin\models\Category;
use common\models\ChildCategory;
use admin\models\AuthItem;
use common\models\SubCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use admin\models\AccessControlList;

/**
 * PriorityitemController implements the CRUD actions for PriorityItem model.
 */
class PriorityItemController extends Controller
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

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                  //  'delete' => ['POST'],
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
     * Lists all PriorityItem models.
     *
     * @return mixed
     */
    public function actionIndex($start = false, $end = false, $status = false, $level = false)
    {
        /* BEGIN Priority created date filter */
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $searchModel = new PriorityitemSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $data['start'], $data['end'], $data['status'], $data['level']);

            return $this->renderPartial('filterindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
               ]);
        }
        /* END Priority created date filter */

            $searchModel = new PriorityitemSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single PriorityItem model.
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
     * Creates a new PriorityItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PriorityItem();
        $category = Category::loadcategoryname();
        $subcategory = Subcategory::loadsubcategoryname();
        $childcategory = ChildCategory::loadchild();

        $priority = VendorItem::find()->select(['item_id','item_name'])
          ->where(['item_status' => 'Active'])
          ->andwhere(['!=', 'trash', 'Deleted'])
              ->all();

        $priorityitem = ArrayHelper::map($priority, 'item_id', 'item_name');

        if ($model->load(Yii::$app->request->post()) && ($model->validate())) {

          $model->priority_start_date = Yii::$app->formatter->asDate(
            $model->priority_start_date, 'php:Y-m-d');

          $model->priority_end_date = Yii::$app->formatter->asDate(
            $model->priority_end_date, 'php:Y-m-d');

          $model->save();

          Yii::$app->session->setFlash('success', 'Priority item added successfully!');

          return $this->redirect(['index']);

        } else {
            return $this->render('create', [
                'model' => $model,
                'priorityitem' => $priorityitem,
                'category' => $category,
                'subcategory' => $subcategory,
                'childcategory' => $childcategory,
            ]);
        }
    }

    /**
     * Updates an existing PriorityItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $vendorname = Vendor::loadvendorname();
        $category = Category::loadcategoryname();
        $subcategory = Subcategory::loadsubcategoryname();
        $childcategory = ChildCategory::loadchild();
        $vendorpriorityitem = VendorItem::vendorpriorityitemitem($model->item_id);

        $priority = VendorItem::find()->select(['item_id','item_name'])
        ->where(['item_status' => 'Active'])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->all();
        $priorityitem = ArrayHelper::map($priority, 'item_id', 'item_name');
        if ($model->load(Yii::$app->request->post()) && ($model->validate())) {

            $model->priority_start_date = Yii::$app->formatter->asDate($model->priority_start_date, 'php:Y-m-d');
            $model->priority_end_date = Yii::$app->formatter->asDate($model->priority_end_date, 'php:Y-m-d');


            $model->save();
            Yii::$app->session->setFlash('success', 'Priority item updated successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                 'model' => $model, 'category' => $category,
                 'subcategory' => $subcategory, 'priorityitem' => $priorityitem, 'childcategory' => $childcategory,
            ]);
        }
    }

     /**
      * Deletes an existing PriorityItem model.
      * If deletion is successful, the browser will be redirected to the 'index' page.
      *
      * @param string $id
      *
      * @return mixed
      */
     public function actionDelete($id)
     {
         $command=PriorityItem::updateAll(['trash' => 'Deleted'],'priority_id= '.$id);
         Yii::$app->session->setFlash('success', 'Priority item deleted successfully!');
         return $this->redirect(['index']);
     }

    /**
     * Finds the PriorityItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return PriorityItem the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PriorityItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public static function actionLoadcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        
        $categoryid = Vendor::find()->select('category_id')
            ->where(['vendor_id' => $data['id']])
            ->andwhere(['category_level' => 0])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->andwhere(['parent_category_id' => null])
          ->one();

        $k = explode(',', $categoryid['category_id']);
        $category = Category::find()->select('category_id,category_name')->where(['category_id' => $k])->all();
        echo  '<option value="">Select...</option>';
        foreach ($category as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
        die; // action used by vendor module also.
    }
    public function actionLoadsubcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $subcategory = Category::find()->select('category_id,category_name')
          ->where(['parent_category_id' => $data['id']])
          ->andwhere(['category_level' => 1])
          ->andwhere(['!=', 'trash', 'Deleted'])
          ->andwhere(['!=', 'parent_category_id', 'null'])->all();
          echo  '<option value="">Select subcategory...</option>';
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
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
          ->andwhere(['!=', 'trash', 'Deleted'])->all();
        echo  '<option value="">Select child category...</option>';
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
        die; // dont remove die, action used by vendor module also.
    }

    public function actionLoaditems()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $itemlist = VendorItem::find()->select(['item_id','item_name'])
            ->where(['item_status' => 'Active'])
            ->andWhere(['category_id' => $data['id2']])
            ->andWhere(['subcategory_id' => $data['id3']])
            ->andWhere(['child_category' => $data['id4']])
            ->andWhere(['!=', 'trash', 'Deleted'])
			      ->all();
        $item = '';
        $count = count($itemlist);
        if (($count < 20) && ($count > 0)) {
        } else {
            $item .= '<div class="admin" style="color:red">No Items Avilable</div>';
        }
            $item .= '<option value="">Select</option>';
        foreach ($itemlist as $key => $val) {
            $item .= '<option value="'.$val['item_id'].'">'.$val['item_name'].'</option>';
        }
        return $item;
    }
    public function actionLoaddatetime()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        if ($data['item']):
			
			$datetime = PriorityItem::find()->select('priority_start_date','priority_end_date')
            ->where(['priority_id' => $data['priority_id']])
            ->andwhere(['item_id' => $data['item']])
            ->andwhere(['!=', 'trash', 'Deleted'])
			->all();
        $k = '';
        $k1 = '';
        foreach ($datetime as $d) {
            $date = $date1 = $d['priority_start_date'];
            $date = date('Y-m-d', strtotime('-2 day', strtotime($date)));
            $end_date = $end_date1 = $d['priority_end_date'];
            $end_date = date('Y-m-d', strtotime('-2 day', strtotime($end_date)));
            while (strtotime($date) <= strtotime($end_date)) {
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                $k[] = $date;
            }
            while (strtotime($date1) <= strtotime($end_date1)) {
                $k1[] = $date1;
                $date1 = date('Y-m-d', strtotime('+1 day', strtotime($date1)));
            }
        }
        $cnt = count($k);
        $as = '<input type="text" id="priorityitem-priority_start_date" class="form-control" name="PriorityItem[priority_start_date]">';
        $ae = '<input type="text" id="priorityitem-priority_end_date" class="form-control" name="PriorityItem[priority_end_date]">';
        echo json_encode(array('date' => $k, 'date1' => $k1, 'count' => $cnt, 'input1' => $as, 'input2' => $ae));
        exit;
        endif;
    }

    public function actionCheckprioritydate()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        if ($data['item']):
         {
            $blocked_dates = $data['blocked_dates'];
            $blocked_dates1 = explode(',', $data['blocked_dates']);
            $start = $data['start'];
            $end = $data['end'];
         }
        while (strtotime($start) <= strtotime($end)) {
            $new_dates[] = $start;
            $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
        }
        $available = 0;

        foreach ($new_dates as $key => $value) {
            if (in_array($value, $blocked_dates1)) {
                echo '1';
                die;
            }
        }
        echo $available;
        die;
        endif;
    }

    public function actionCheckitem()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            print_r($data);
            die;
        }
    }
    public function actionStatus()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $ids = implode('","', $data['keylist']);
        //print_r($ids);die;
        if ($data['status'] == 'Normal') {
			   $command=PriorityItem::updateAll(['priority_level' => 'Normal'],['IN', 'priority_id', $data['keylist']]);
            if ($command) {
                Yii::$app->session->setFlash('success', 'Priority item level updated!');
            } else {
                Yii::$app->session->setFlash('danger', 'Something went wrong');
            }
        } elseif ($data['status'] == 'Super') {
			   $command=PriorityItem::updateAll(['priority_level' => 'Super'],['IN', 'priority_id', $data['keylist']]);
			if ($command) {
                Yii::$app->session->setFlash('success', 'Priority item level updated!');
            } else {
                Yii::$app->session->setFlash('danger', 'Something went wrong');
            }
        }
    }

    public function actionBlockpriority()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'Active' ? 'Inactive' : 'Active');
        $command=PriorityItem::updateAll(['priority_level' => $status],['priority_id'=>$data['aid']]);
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }
}
