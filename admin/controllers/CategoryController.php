<?php
    
namespace admin\controllers;

use Yii;
use yii\helpers\VarDumper;
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
        $categories = CategoryPath::find()
            ->select("GROUP_CONCAT(c1.category_name ORDER BY {{%category_path}}.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS category_name, c2.sort, {{%category_path}}.category_id as ID")
            ->leftJoin('whitebook_category c1', 'c1.category_id = whitebook_category_path.path_id')
            ->leftJoin('whitebook_category c2', 'c2.category_id = whitebook_category_path.category_id')
            ->where(['c2.trash'=>'Default'])
            ->groupBy('{{%category_path}}.category_id')
            ->orderBy('category_name')
            ->asArray()
            ->all();

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $categories,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['category_name','sort'],
            ],
        ]);

        return $this->render('index', [
            'provider' => $provider,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $max_sort = Category::find()
                ->select('MAX(category_id) as sort')
                ->where(['trash' => 'Default'])
                ->andWhere(['category_level' =>0])
                ->asArray()
                ->one();

            $sort = ($max_sort['sort'] + 1);
            $model->sort = $sort;

            $model->save();

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

            $model->category_level = $level;
            $model->save();

            Yii::$app->session->setFlash('success', 'Category created successfully!');

            Yii::info('[New Category] Admin created new category ' . $model->category_name, __METHOD__);

            return $this->redirect(['index']);

        } else {

            $categories = CategoryPath::find()
                ->select("GROUP_CONCAT(c1.category_name ORDER BY {{%category_path}}.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS category_name, {{%category_path}}.category_id")
                ->leftJoin('whitebook_category c1', 'c1.category_id = whitebook_category_path.path_id')
                ->leftJoin('whitebook_category c2', 'c2.category_id = whitebook_category_path.category_id')
                ->where(['c2.trash'=>'Default'])
                ->groupBy('{{%category_path}}.category_id')
                ->orderBy('category_name')
                ->asArray()
                ->all();


            return $this->render('create', [
                'model' => $model,
                'categories' => $categories,
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
        $categories = CategoryPath::find()
            ->select("GROUP_CONCAT(c1.category_name ORDER BY {{%category_path}}.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS category_name, {{%category_path}}.category_id")
            ->leftJoin('whitebook_category c1', 'c1.category_id = whitebook_category_path.path_id')
            ->leftJoin('whitebook_category c2', 'c2.category_id = whitebook_category_path.category_id')
            ->where(['!=','{{%category_path}}.category_id',$id])
            ->groupBy('{{%category_path}}.category_id')
            ->where(['c2.trash'=>'Default'])
            ->orderBy('category_name')
            ->asArray()
            ->all();

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

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

            $model->category_level = $level;
            $model->save();

            Yii::$app->session->setFlash('success', 'Category updated successfully!');
            Yii::info('[Category Updated] Admin updated category ' . $model->category_name, __METHOD__);

            return $this->redirect(['index']);

        } else {

            return $this->render('update', [
                'model' => $model,
                'categories' => $categories,
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
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->andwhere(['!=', 'parent_category_id', 'null'])
            ->all();

        echo  '<option value="">Select...</option>';
        foreach ($subcategory as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }
}
