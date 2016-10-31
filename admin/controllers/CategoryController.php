<?php
    
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use common\models\Customer;
use common\models\ChildCategory;
use common\models\SubCategory;
use common\models\CategoryPath;
use common\models\VendorItemToCategory;
use admin\models\Image;
use admin\models\Admin;
use admin\models\AuthItem;
use admin\models\Category;
use admin\models\Vendor;
use admin\models\CategorySearch;
use admin\models\VendorItem;
use admin\models\AccessControlList;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
                //   'delete' => ['POST'],
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
     * Lists all Category models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionManage_subcategory()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->subcategory_search(Yii::$app->request->queryParams);

        return $this->render('subcategory_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChild_category_index()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->childcategory_search(Yii::$app->request->queryParams);

        return $this->render('child_category_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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

    public function actionSort_sub_category()
    {
        $request = Yii::$app->request;

        $sort = $request->post('sort_val');
        $cat_id = $request->post('cat_id');
        $p_cat_id = $request->post('p_cat_id');

        $category = Category::updateAll(
            ['sort' => $sort],
            ['category_id= '.$cat_id,'parent_category_id= '.$p_cat_id]
        );

        if ($category) {
            Yii::$app->session->setFlash('success', 'Category sort order updated successfully!');
            echo 1;
        } else {
            echo 0;
        }
    }

    public function actionSort_category()
    {
        $request = Yii::$app->request;

        $sort = $request->post('sort_val');
        $cat_id = $request->post('cat_id');

        $category = Category::updateAll(
            ['sort' => $sort],
            ['category_id' => $cat_id]
        );

        if ($category) {
            Yii::$app->session->setFlash('success', 'Category sort order updated successfully!');
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
            $model = new Category();
            $model->scenario = 'register';
            
            if($model->load(Yii::$app->request->post()))
            {
                $model->validate();
                
                $model->category_allow_sale = (Yii::$app->request->post()['Category']['category_allow_sale']) ? 'yes' : 'no';

                $model->category_name = strtolower($model->category_name);
                
                $max_sort = Category::find()
                    ->select('MAX(category_id) as sort')
        			->where(['trash' => 'Default'])
        			->andWhere(['category_level' =>0])
                    ->asArray()
        			->one();

                $sort = ($max_sort['sort'] + 1);
                $model->sort = $sort;
                
                $model->category_level = Category::FIRST_LEVEL;
                $model->save(false);
                
                // MySQL Hierarchical Data Closure Table Pattern for category 

                $level = 0;

                $paths = CategoryPath::find()
                        ->where(['category_id' => $model->parent_category_id])
                        ->orderBy('level ASC')
                        ->all();

                foreach ($paths as $path) {

                    $cp = new CategoryPath();
                    $cp->category_id = $model->category_id;
                    $cp->level = $level;
                    $cp->path_id = $path->path_id;
                    $cp->save();

                    $level++;
                }

                $cp = new CategoryPath();
                $cp->category_id = $model->category_id;
                $cp->path_id = $model->category_id;
                $cp->level = $level;
                $cp->save();

                Yii::$app->session->setFlash('success', 'Category created successfully!');
                
                Yii::info('[New Category] Admin created new category '.$model->category_name, __METHOD__);
                
                return $this->redirect(['index']);

            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
    }

    public function actionCreate_subcategory()
    {
            $model = new SubCategory();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                
                $model->category_allow_sale = (Yii::$app->request->post()['SubCategory']['category_allow_sale']) ? 'yes' : 'no';
                
                 // get the max sort order
		        $max_sort = Category::find()
                    ->select('max(sort) as sort')
    				->where(['parent_category_id' => Yii::$app->request->post()['SubCategory']['parent_category_id']])
    				->andWhere(['trash' => 'default'])
    				->andWhere(['category_level' => Category::SECOND_LEVEL])
    				->asArray()
    				->one();

                $sort = ($max_sort['sort'] + 1);

                $model->sort = $sort;
                $model->category_level = Category::SECOND_LEVEL;
                $model->save(false);

                // MySQL Hierarchical Data Closure Table Pattern for category 

                $level = 0;

                $paths = CategoryPath::find()
                        ->where(['category_id' => $model->parent_category_id])
                        ->orderBy('level ASC')
                        ->all();

                foreach ($paths as $path) {

                    $cp = new CategoryPath();
                    $cp->category_id = $model->category_id;
                    $cp->level = $level;
                    $cp->path_id = $path->path_id;
                    $cp->save();

                    $level++;
                }

                $cp = new CategoryPath();
                $cp->category_id = $model->category_id;
                $cp->path_id = $model->category_id;
                $cp->level = $level;
                $cp->save();

                Yii::$app->session->setFlash('success', 'Subcategory added successfully!');
                Yii::info('[New Subcategory] Admin created new sub category '.$model->category_name, __METHOD__);

                return $this->redirect(['manage_subcategory']);

            } else {
                
                $subcategory = SubCategory::find()
                    ->where(['parent_category_id' => null])
                    ->andwhere(['trash' => 'default'])
                    ->andwhere(['category_allow_sale' => 'yes'])
                    ->all();

                $subcategory = ArrayHelper::map($subcategory, 'category_id', 'category_name');

                return $this->render('subcategory_create', [
                    'model' => $model, 
                    'subcategory' => $subcategory
                ]);
            }
        }

    public function actionChild_category_create()
    {
            $model = new ChildCategory();
    
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                
                $string = str_replace(' ', '-', Yii::$app->request->post()['ChildCategory']['category_name']); // Replaces all spaces with hyphens.
                
                $model->slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
                $model->category_allow_sale = (Yii::$app->request->post()['ChildCategory']['category_allow_sale']) ? 'yes' : 'no';
                
                $model->parent_category_id = Yii::$app->request->post()['ChildCategory']['subcategory_id'];
                $model->category_level = '2';
                $parent_category_id = Yii::$app->request->post()['ChildCategory']['subcategory_id'];
                $max_sort = Category::find()
                    ->select('max(sort) as sort')
        			->where(['parent_category_id' => $parent_category_id])
        			->andwhere(['trash' => 'default'])
        			->andwhere(['category_level' => Category::THIRD_LEVEL])
        			->asArray()
        			->one();

                $sort = ($max_sort['sort'] + 1);
                $model->sort = $sort;
                $model->save(false);

                // MySQL Hierarchical Data Closure Table Pattern for category 

                $level = 0;

                $paths = CategoryPath::find()
                        ->where(['category_id' => $model->parent_category_id])
                        ->orderBy('level ASC')
                        ->all();

                foreach ($paths as $path) {

                    $cp = new CategoryPath();
                    $cp->category_id = $model->category_id;
                    $cp->level = $level;
                    $cp->path_id = $path->path_id;
                    $cp->save();

                    $level++;
                }

                $cp = new CategoryPath();
                $cp->category_id = $model->category_id;
                $cp->path_id = $model->category_id;
                $cp->level = $level;
                $cp->save();

                Yii::$app->session->setFlash('success', 'Child category added successfully!');
                Yii::info('[New Subcategory] Admin created new sub category '.$model->category_name, __METHOD__);

                return $this->redirect(['child_category_index']);

            } else {
                
                $category = Category::find()
                    ->where(['parent_category_id' => null])
                    ->andwhere(['trash' => 'default'])
                    ->andwhere(['category_allow_sale' => 'yes'])
                    ->andwhere(['category_level' => Category::FIRST_LEVEL])
                    ->all();

                $category = ArrayHelper::map($category, 'category_id', 'category_name');

                return $this->render('child_category_create', [
                    'model' => $model, 
                    'category' => $category
                ]);
            }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
            $model = $this->findModel($id);
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
    
                $model->category_name = strtolower($model->category_name);
                
                // MySQL Hierarchical Data Closure Table Pattern for category 
                CategoryPath::deleteAll(['category_id' => $model->category_id]);

                $level = 0;

                $paths = CategoryPath::find()
                        ->where(['category_id' => $model->parent_category_id])
                        ->orderBy('level ASC')
                        ->all();

                foreach ($paths as $path) {

                    $cp = new CategoryPath();
                    $cp->category_id = $model->category_id;
                    $cp->level = $level;
                    $cp->path_id = $path->path_id;
                    $cp->save();

                    $level++;
                }

                $cp = new CategoryPath();
                $cp->category_id = $model->category_id;
                $cp->path_id = $model->category_id;
                $cp->level = $level;
                $cp->save();


                Yii::$app->session->setFlash('success', 'Category updated successfully!');
                Yii::info('[Category Updated] Admin updated category '.$model->category_name, __METHOD__);
                
                return $this->redirect(['index']);
    
            } else {
    
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
    }

    public function actionSubcategory_update($id)
    {
            $model = $this->findsubModel($id);
            $userid = Yii::$app->user->getId();
            $model1 = new Image();
        
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        
                $model->category_allow_sale = (Yii::$app->request->post()['SubCategory']['category_allow_sale']) ? 'yes' : 'no';
                $model->save();

                // MySQL Hierarchical Data Closure Table Pattern for category 
                CategoryPath::deleteAll(['category_id' => $model->category_id]);

                $level = 0;

                $paths = CategoryPath::find()
                        ->where(['category_id' => $model->parent_category_id])
                        ->orderBy('level ASC')
                        ->all();

                foreach ($paths as $path) {

                    $cp = new CategoryPath();
                    $cp->category_id = $model->category_id;
                    $cp->level = $level;
                    $cp->path_id = $path->path_id;
                    $cp->save();

                    $level++;
                }

                $cp = new CategoryPath();
                $cp->category_id = $model->category_id;
                $cp->path_id = $model->category_id;
                $cp->level = $level;
                $cp->save();


                Yii::$app->session->setFlash('success', 'Subcategory updated successfully!');
                Yii::info('[Subcategory Updated] Admin updated sub category '.$model->category_name, __METHOD__);
        
                return $this->redirect(['manage_subcategory']);

            } else {
                
                $subcategory = SubCategory::find()
                    ->where(['parent_category_id' => null])
                    ->andWhere(['trash'=>'Default'])
                    ->all();

                $subcategory = ArrayHelper::map($subcategory, 'category_id', 'category_name');
                
                return $this->render('subcategory_update', [
                    'model' => $model, 
                    'subcategory' => $subcategory, 
                    'userid' => $userid, 
                    'id' => $id
                ]);
            }
    }

    public function actionChild_category_update($id)
    {
        $model = $this->findChildModel($id);
        $userid = Yii::$app->user->getId();
        $model1 = new Image();

        if ($model->load(Yii::$app->request->post())) {

            $string = str_replace(' ', '-', Yii::$app->request->post()['ChildCategory']['category_name']); // Replaces all spaces with hyphens.
            $model->slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
            $model->category_allow_sale = (Yii::$app->request->post()['ChildCategory']['category_allow_sale']) ? 'yes' : 'no';
            $model->parent_category_id = Yii::$app->request->post()['ChildCategory']['subcategory_id'];
            $model->category_level = Category::THIRD_LEVEL;
            $model->category_name;
            $model->save(false);

            // MySQL Hierarchical Data Closure Table Pattern for category
            CategoryPath::deleteAll(['category_id' => $model->category_id]);

            $level = 0;

            $paths = CategoryPath::find()
                    ->where(['category_id' => $model->parent_category_id])
                    ->orderBy('level ASC')
                    ->all();

            foreach ($paths as $path) {

                $cp = new CategoryPath();
                $cp->category_id = $model->category_id;
                $cp->level = $level;
                $cp->path_id = $path->path_id;
                $cp->save();

                $level++;
            }

            $cp = new CategoryPath();
            $cp->category_id = $model->category_id;
            $cp->path_id = $model->category_id;
            $cp->level = $level;
            $cp->save();


            Yii::$app->session->setFlash('success', 'Child category updated successfully!');
            Yii::info('[Subcategory Updated] Admin updated sub category '.$model->category_name, __METHOD__);

            return $this->redirect(['child_category_index']);

        } else {

            $parentcategory = Category::find()
                ->where(['parent_category_id' => null])
                ->andwhere(['trash' => 'default'])
                ->andwhere(['category_allow_sale' => 'yes'])
                ->andwhere(['category_level' => Category::FIRST_LEVEL])
                ->all();

            $subcategory = Category::find()
                ->where(['category_id' => $model->parent_category_id])
                ->andwhere(['trash' => 'default'])
                ->andwhere(['category_allow_sale' => 'yes'])
                ->andwhere(['category_level' => Category::SECOND_LEVEL])
                ->all();

            $parentid = $subcategory[0]['parent_category_id'];

            $subcategory = ArrayHelper::map($subcategory, 'category_id', 'category_name');

            $subcategory_id = $model->parent_category_id;
            $parentcategory = ArrayHelper::map($parentcategory, 'category_id', 'category_name');

            return $this->render('child_category_update', [
                'model' => $model,
                'parentcategory' => $parentcategory,
                'userid' => $userid,
                'parentid' => $parentid,
                'subcategory_id' => $subcategory_id,
                'id' => $id,
                'subcategory' => $subcategory
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!$model) {
            Yii::$app->session->setFlash('danger', 'Sorry, This category not available!');

            return $this->redirect(['index']);
        }

        //check assing to items
        $vendor_item = VendorItemToCategory::find()->where(['category_id' => $id])->count();

        if ($vendor_item) {

            Yii::$app->session->setFlash('danger', 'Sorry, This category mapped with item.');

            return $this->redirect(['index']);
        }

        //update all child and self
        $affected = Category::updateAll(
            ['trash' => 'Deleted'],
            '{{%category}}.category_id IN (select category_id from {{%category_path}} where path_id = "'.$id.'")'
        );

        if ($affected) {

            Yii::$app->session->setFlash('success', 'Category deleted successfully!');

            return $this->redirect(['index']);

        } else {

            Yii::$app->session->setFlash('success', 'Category delete failed!');

            return $this->redirect(['index']);
        }
    }

    public function actionCategory_delete($id)
    {
        Category::updateAll(
            ['trash' => 'Deleted'],
            '{{%category}}.category_id IN (select category_id from {{%category_path}} where path_id = "'.$id.'")'
        );

        Yii::$app->session->setFlash('success', 'Subcategory deleted successfully!');

        return $this->redirect(['index']);
    }

    public function actionSubcategory_delete($id)
    {
        //check if category exists
        $model = $this->findModel($id);

        if(!$model) {
            Yii::$app->session->setFlash('danger', 'Sorry, This category not available!');
            return $this->redirect(['manage_subcategory']);
        }

        //check if assigned to items
        $vendor_item = VendorItemToCategory::find()
            ->where(['category_id' => $id])
            ->count();

        if (!empty($vendor_item)) {
            Yii::$app->session->setFlash('danger', 'Sorry, This category mapped with item.');
            return $this->redirect(['manage_subcategory']);
        }

        //update all child and self
        $affected = Category::updateAll(
            ['trash' => 'Deleted'],
            '{{%category}}.category_id IN (select category_id from {{%category_path}} where path_id = "'.$id.'")'
        );

        if ($affected) {

            Yii::$app->session->setFlash('success', 'Subcategory deleted successfully!');
            return $this->redirect(['manage_subcategory']);

        } else {

            Yii::$app->session->setFlash('success', 'Subcategory delete failed!');
            return $this->redirect(['manage_subcategory']);
        }
    }

    public function actionChildcategory_delete($id)
    {

        $model = $this->findModel($id);

        if(!$model) {
            Yii::$app->session->setFlash('danger', 'Sorry, This category not available!');
            return $this->redirect(['child_category_index']);
        }

        $vendor_item = VendorItemToCategory::find()
            ->where(['category_id' => $id])
            ->count();

        if (!empty($vendor_item)) {
            Yii::$app->session->setFlash('danger', 'Sorry, This category mapped with item.');
            return $this->redirect(['child_category_index']);
        }

        $affected = Category::updateAll(
            ['trash' => 'Deleted'],
            '{{%category}}.category_id IN (select category_id from {{%category_path}} where path_id = "'.$id.'")'
        );

        if ($affected) {

            Yii::$app->session->setFlash('success', 'Child category deleted successfully!');
            return $this->redirect(['child_category_index']);

        } else {

            Yii::$app->session->setFlash('success', 'Child category delete failed!');
            return $this->redirect(['child_category_index']);
        }
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Category the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findsubModel($id)
    {
        if (($model = Subcategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findChildModel($id)
    {
        if (($model = ChildCategory::findOne($id)) !== null) {
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

        $status = ($data['status'] == 'yes' ? 'no' : 'yes');

        $category = Category::updateAll(['category_allow_sale' => $status],[
            'category_id' => $data['cid']
        ]);

        $category = Category::updateAll(['category_allow_sale' => $status],[
            'parent_category_id' => $data['cid']
        ]);

        $sub_category = Category::find()
            ->select('category_id')
            ->where(['parent_category_id' => $data['cid']])
            ->all();
        
        foreach ($sub_category as $cat) {

			$category = Category::updateAll(['category_allow_sale' => $status], [
                'parent_category_id' => $cat['category_id']
            ]);
        }

        if ($status == 'yes') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionSubcategory_block()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();
        
        $status = ($data['status'] == 'yes' ? 'no' : 'yes');

        $category = Category::updateAll(['category_allow_sale' => $status], [
            'category_id' => $data['cid']
        ]);

        $category = Category::updateAll(['category_allow_sale' => $status], [
            'parent_category_id' => $data['cid']
        ]);
        
        if ($status == 'yes') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionLoadcategory()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        $category = Category::find()->select('category_id,category_name')->where(['vendor_id' => $data['id']])->all();
        foreach ($category as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }

    /* Load category for particular vendor assigned category*/

    public function actionVendorcategory()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $data = Yii::$app->request->post();

        $vendor = Vendor::find()
            ->select('category_id')
            ->where(['vendor_id' => $data['vendor_id']])
            ->all();
        
        $vendor_id = $vendor[0]['category_id'];
        $vendor_exp = explode(',', $vendor_id);
        $vendor_imp = implode('","', $vendor_exp);
        
        $categories  = Category::find()
            ->select(['category_id', 'category_name'])
            ->where(['category_id' => $vendor_imp])
            ->all();

        echo  '<option value="">Select</option>';
        foreach ($categories as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }

    public function actionLoadsubcategory()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        
        $data = Yii::$app->request->post();

        $subcategory = Category::find()->select('category_id,category_name')
            ->where(['parent_category_id' => $data['id']])
            ->andwhere(['!=', 'category_allow_sale', 'no'])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->andwhere(['!=', 'parent_category_id', 'null'])
            ->all();

        echo  '<option value="">Select...</option>';
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }
}
