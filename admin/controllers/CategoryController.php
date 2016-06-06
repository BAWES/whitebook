<?php
    
namespace admin\controllers;

use Yii;
use admin\models\Image;
use admin\models\Admin;
use admin\models\Authitem;
use admin\models\Category;
use admin\models\Vendor;
use common\models\ChildCategory;
use common\models\SubCategory;
use admin\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use admin\models\Vendoritem;
use common\models\Customer;

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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'category_delete', 'index',
                       'loadcategory', 'manage_subcategory', 'create_subcategory', 'subcategory_block',
                       'subcategory_delete', 'subcategory_update', 'vendorcategory', 'sort_sub_category', 'sort_category',
                       'child_category_index', 'child_category_create', 'loadsubcategory', 'child_category_update', 'childcategory_delete', ],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'delete' => ['post'],
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
        $access = Authitem::AuthitemCheck('4', '3');
        if (yii::$app->user->can($access)) {
            $searchModel = new CategorySearch();
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

    public function actionManage_subcategory()
    {
        $access = Authitem::AuthitemCheck('4', '3');
        if (yii::$app->user->can($access)) {
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->subcategory_search(Yii::$app->request->queryParams);

            return $this->render('subcategory_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionChild_category_index()
    {
        $access = Authitem::AuthitemCheck('4', '3');
        if (yii::$app->user->can($access)) {
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->childcategory_search(Yii::$app->request->queryParams);

            return $this->render('child_category_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
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
        $sort = $_POST['sort_val'];
        $cat_id = $_POST['cat_id'];
        $p_cat_id = $_POST['p_cat_id'];
        $category=Category::updateAll(['sort' => $sort],['category_id= '.$cat_id,'parent_category_id= '.$p_cat_id]);

        if ($category) {
            Yii::$app->session->setFlash('success', 'Category sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

    public function actionSort_category()
    {
        $sort = $_POST['sort_val'];
        $cat_id = $_POST['cat_id'];
        $category=Category::updateAll(['sort' => $sort],['category_id= '.$cat_id]);
        if ($category) {
            Yii::$app->session->setFlash('success', 'Category sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
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
        $access = Authitem::AuthitemCheck('1', '3');
        if (yii::$app->user->can($access)) {
            $model = new Category();
            $model->scenario = 'register';
            if($model->load(Yii::$app->request->post()))
            {
                $model->validate();
            if (isset($_FILES['Category']['name']['category_icon'])) {
                $file = UploadedFile::getInstances($model, 'category_icon');
                if(!empty($file)){
                    $model->category_icon = $file[0]->tempName;
                }
            } else {
                $model->scenario = '';
            }

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
                $model->save(false);
                $categoryid = $model->category_id;
                $base = Yii::$app->basePath;

                if ($file) {
                    foreach ($file as $files) {
                         $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;
                        //Resize file using imagine
                        $resize = true;
                        if($resize){
                            /* Begin Product image resolution 25 */
                            $newTmpName2 = $files->tempName . "." . $files->extension;
                            $imagine = new \Imagine\Gd\Imagine();
                            $image_30 = $imagine->open($files->tempName);
                            $image_30->resize($image_30->getSize()->widen(30));
                            $image_30->save($newTmpName2);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName2;
                            $awsResult1 = Yii::$app->resourceManager->save($files, Category::CATEGORY_ICON . $filename);
                        }
                    }
                }
                if ($file) {
                    $category=Category::updateAll(['category_icon' => $filename],['category_id'=>$categoryid]);
                }
                echo Yii::$app->session->setFlash('success', 'Category created successfully!');
                Yii::info('[New Category] Admin created new category '.$model->category_name, __METHOD__);

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionCreate_subcategory()
    {
        $access = Authitem::AuthitemCheck('1', '3');
        if (yii::$app->user->can($access)) {
            $model = new SubCategory();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                 $model->category_allow_sale = (Yii::$app->request->post()['SubCategory']['category_allow_sale']) ? 'yes' : 'no';
                 // get the max sort order
		         $max_sort = Category::find()
                 ->select('max(sort) as sort')
				->where(['parent_category_id' => Yii::$app->request->post()['SubCategory']['parent_category_id']])
				->andWhere(['trash' => 'default'])
				->andWhere(['category_level' => '1'])
				->asArray()
				->one();
                $sort = ($max_sort['sort'] + 1);

                $model->sort = $sort;
                $model->category_level = '1';
                $model->save(false);
                echo Yii::$app->session->setFlash('success', 'Subcategory added successfully!');
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
                'model' => $model, 'subcategory' => $subcategory,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }
    public function actionChild_category_create()
    {
        $access = Authitem::AuthitemCheck('1', '3');
        if (yii::$app->user->can($access)) {
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
				->andwhere(['category_level' => '2'])
				->asArray()
				->one();
                $sort = ($max_sort['sort'] + 1);
                $model->sort = $sort;
                $model->save(false);
                echo Yii::$app->session->setFlash('success', 'Child category added successfully!');
                Yii::info('[New Subcategory] Admin created new sub category '.$model->category_name, __METHOD__);

                return $this->redirect(['child_category_index']);
            } else {
                $category = Category::find()
            ->where(['parent_category_id' => null])
            ->andwhere(['trash' => 'default'])
            ->andwhere(['category_allow_sale' => 'yes'])
            ->andwhere(['category_level' => '0'])
            ->all();
                $category = ArrayHelper::map($category, 'category_id', 'category_name');

                return $this->render('child_category_create', [
                'model' => $model, 'category' => $category,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
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
        $access = Authitem::AuthitemCheck('2', '3');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
        //$model->scenario = 'update';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->category_name = strtolower($model->category_name);
            $categoryid = $model->category_id;
            $base = Yii::$app->basePath;
            $file = UploadedFile::getInstances($model, 'category_icon');
                if ($file) {
                    foreach ($file as $files) {
                       // $files->saveAs($base.'/web/uploads/subcategory_icon/category_'.$categoryid.'.png');
                         $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;
                        //Resize file using imagine
                        $resize = true;
                        if($resize){
                            /* Begin Product image resolution 25 */
                            $newTmpName2 = $files->tempName . "." . $files->extension;
                            $imagine = new \Imagine\Gd\Imagine();
                            $image_30 = $imagine->open($files->tempName);
                            $image_30->resize($image_30->getSize()->widen(30));
                            $image_30->save($newTmpName2);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName2;
                            $awsResult1 = Yii::$app->resourceManager->save($files, Category::CATEGORY_ICON . $filename);
                        }
                    }
                }
            if ($file) {
                //$file_name = 'category_'.$categoryid.'.png';
                $category=Category::updateAll(['category_icon' => $filename],['category_id'=>$categoryid]);
            }
            echo Yii::$app->session->setFlash('success', 'Category updated successfully!');
            Yii::info('[Category Updated] Admin updated category '.$model->category_name, __METHOD__);

            return $this->redirect(['index']);
            //return $this->redirect(['view', 'id' => $model->category_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionSubcategory_update($id)
    {

        $access = Authitem::AuthitemCheck('2', '3');
        if (yii::$app->user->can($access)) {
            $model = $this->findsubModel($id);
            $userid = Yii::$app->user->getId();
            $model1 = new Image();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->category_allow_sale = (Yii::$app->request->post()['SubCategory']['category_allow_sale']) ? 'yes' : 'no';
                $model->save();
                echo Yii::$app->session->setFlash('success', 'Subcategory updated successfully!');
                Yii::info('[Subcategory Updated] Admin updated sub category '.$model->category_name, __METHOD__);
                return $this->redirect(['manage_subcategory']);
            } else {
                $subcategory = SubCategory::find()
                ->where(['parent_category_id' => null])
                ->andWhere(['trash'=>'Default'])
                ->all();
                $subcategory = ArrayHelper::map($subcategory, 'category_id', 'category_name');
                return $this->render('subcategory_update', ['model' => $model, 'subcategory' => $subcategory, 'userid' => $userid, 'id' => $id]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionChild_category_update($id)
    {
        $access = Authitem::AuthitemCheck('2', '3');
        if (yii::$app->user->can($access)) {
            $model = $this->findChildModel($id);
            $userid = Yii::$app->user->getId();
            $model1 = new Image();

            if ($model->load(Yii::$app->request->post())) {
                $string = str_replace(' ', '-', Yii::$app->request->post()['ChildCategory']['category_name']); // Replaces all spaces with hyphens.
            $model->slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
            $model->category_allow_sale = (Yii::$app->request->post()['ChildCategory']['category_allow_sale']) ? 'yes' : 'no';
                $model->parent_category_id = Yii::$app->request->post()['ChildCategory']['subcategory_id'];
                $model->category_level = '2';
                $model->category_name;
                $model->save(false);
                echo Yii::$app->session->setFlash('success', 'Child category updated successfully!');
                Yii::info('[Subcategory Updated] Admin updated sub category '.$model->category_name, __METHOD__);

                return $this->redirect(['child_category_index']);
            } else {
                $parentcategory = Category::find()
            ->where(['parent_category_id' => null])
            ->andwhere(['trash' => 'default'])
            ->andwhere(['category_allow_sale' => 'yes'])
            ->andwhere(['category_level' => '0'])
            ->all();
                $subcategory = Category::find()
            ->where(['category_id' => $model->parent_category_id])
            ->andwhere(['trash' => 'default'])
            ->andwhere(['category_allow_sale' => 'yes'])
            ->andwhere(['category_level' => '1'])
            ->all();
                $parentid = $subcategory[0]['parent_category_id'];
                $subcategory = ArrayHelper::map($subcategory, 'category_id', 'category_name');
            //echo $model->parent_category_id;die;
            //$model = Category::find()->where(['category_id'=>$parentid])->one();
            $subcategory_id = $model->parent_category_id;
                $parentcategory = ArrayHelper::map($parentcategory, 'category_id', 'category_name');

                return $this->render('child_category_update', ['model' => $model, 'parentcategory' => $parentcategory, 'userid' => $userid, 'parentid' => $parentid, 'subcategory_id' => $subcategory_id,
            'id' => $id, 'subcategory' => $subcategory, ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
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
        $access = Authitem::AuthitemCheck('3', '3');
        if (yii::$app->user->can($access)) {
            $vendor_item = Vendoritem::find()->where(['category_id' => $id, 'trash' => 'Default'])->count();
            if (!empty($vendor_item)) {
                echo Yii::$app->session->setFlash('danger', 'Sorry, This category mapped with item.');

                return $this->redirect(['index']);
            }

            $model = $this->findModel($id);
            $parentcategory = Category::find()->select('category_id,parent_category_id')->where(['parent_category_id' => $id])->all();
            $subcategory = array();
            if (count($parentcategory) && (!empty($parentcategory))) {
                $subcategory = Category::find()->select('category_id,parent_category_id')->where(['parent_category_id' => $parentcategory[0]['category_id']])->all();
                $category=Category::updateAll(['trash' => 'Deleted'],['category_id'=>$parentcategory[0]['category_id']]);
            }

            if (count($subcategory) && (!empty($subcategory))) {
                $childcategory = Category::find()->select('category_id,parent_category_id')->where(['parent_category_id' => $subcategory[0]['category_id']])->all();
                $category=Category::updateAll(['trash' => 'Deleted'],['category_id'=>$subcategory[0]['category_id']]);
            }
            $category=Category::updateAll(['trash' => 'Deleted'],['category_id'=>$id]);
            if ($category) {
                echo Yii::$app->session->setFlash('success', 'Category deleted successfully!');

                return $this->redirect(['index']);
            } else {
                echo Yii::$app->session->setFlash('success', 'Category delete failed!');
                return $this->redirect(['index']);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionCategory_delete($id)
    {
        $access = Authitem::AuthitemCheck('3', '3');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            var_dump(Yii::$app->request->post());
            $model->trash = 'Deleted';
            $model->load(Yii::$app->request->post());
            $model->save();
            echo Yii::$app->session->setFlash('success', 'Subcategory deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionSubcategory_delete($id)
    {
        $access = Authitem::AuthitemCheck('3', '3');
        if (yii::$app->user->can($access)) {
            $vendor_item = Vendoritem::find()->where(['subcategory_id' => $id, 'trash' => 'Default'])->count();
            if (!empty($vendor_item)) {
                echo Yii::$app->session->setFlash('danger', 'Sorry, This category mapped with item.');

                return $this->redirect(['manage_subcategory']);
            }
            $model = $this->findModel($id);
            $parentcategory = Category::find()->select('category_id,parent_category_id')->where(['parent_category_id' => $id])->all();
            if (count($parentcategory)) {
                $subcategory = Category::find()->select('category_id,parent_category_id')->where(['parent_category_id' => $parentcategory[0]['category_id']])->all();
                $command=Category::updateAll(['trash' => 'Deleted'],['category_id'=>$parentcategory[0]['category_id']]);
            }
            $category=Category::updateAll(['trash' => 'Deleted'],['category_id'=>$id]);
            if ($category) {
                echo Yii::$app->session->setFlash('success', 'Subcategory deleted successfully!');

                return $this->redirect(['manage_subcategory']);
            } else {
                echo Yii::$app->session->setFlash('success', 'Subcategory delete failed!');

                return $this->redirect(['manage_subcategory']);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/manage_subcategory']);
        }
    }

    public function actionChildcategory_delete($id)
    {
        $access = Authitem::AuthitemCheck('3', '3');
        if (yii::$app->user->can($access)) {
            $vendor_item = Vendoritem::find()->where(['child_category' => $id, 'trash' => 'Default'])->count();
            if (!empty($vendor_item)) {
                echo Yii::$app->session->setFlash('danger', 'Sorry, This category mapped with item.');

                return $this->redirect(['child_category_index']);
            }

            $model = $this->findModel($id);
            $category=Category::updateAll(['trash' => 'Deleted'],['category_id'=>$id]);

            if ($category) {
                echo Yii::$app->session->setFlash('success', 'Child category deleted successfully!');

                return $this->redirect(['child_category_index']);
            } else {
                echo Yii::$app->session->setFlash('success', 'Child category delete failed!');

                return $this->redirect(['child_category_index']);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/child_category_index']);
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
        $category=Category::updateAll(['category_allow_sale' => $status],['category_id'=>$data['cid']]);
        $category=Category::updateAll(['category_allow_sale' => $status],['parent_category_id'=>$data['cid']]);

        $sub_category = Category::find()->select('category_id')->where(['parent_category_id' => $data['cid']])->all();
        foreach ($sub_category as $cat) {

			$category=Category::updateAll(['category_allow_sale' => $status],['parent_category_id'=>$cat['category_id']]);

        }
        if ($status == 'yes') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionSubcategory_block()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $status = ($data['status'] == 'yes' ? 'no' : 'yes');

        $category=Category::updateAll(['category_allow_sale' => $status],['category_id'=>$data['cid']]);
        $category=Category::updateAll(['category_allow_sale' => $status],['parent_category_id'=>$data['cid']]);
        if ($status == 'yes') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    public function actionLoadcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $category = Category::find()->select('category_id,category_name')->where(['vendor_id' => $data['id']])->all();
        foreach ($category as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }

    /* Load category for particular vendor assigned category*/

    public function actionVendorcategory()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $vendor = Vendor::find()->select('category_id')->where(['vendor_id' => $data['vendor_id']])->all();
        //print_r($vendor);die;
        $vendor_id = $vendor[0]['category_id'];
        $vendor_exp = explode(',', $vendor_id);
        $vendor_imp = implode('","', $vendor_exp);
        //$category = Category::findAll([100, 101, 123, 124]);
        $categories  = Category::find()
        ->select(['category_id', 'category_name'])
        ->where(['category_id' => $vendor_imp])
        ->all();
        echo  '<option value="">Select</option>';
        foreach ($categories as $key => $val) {
            echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
        }
    }
    /*  Load sub category related to parent category ID ... in create child category  */
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
}
