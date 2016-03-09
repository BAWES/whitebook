<?php

namespace backend\modules\admin\controllers;

use Yii;
use backend\models\Image;
use backend\models\Admin;
use backend\models\Authitem;
use backend\models\Advertcategory;
use backend\models\Category;
use backend\models\AdvertcategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * AdvertcategoryController implements the CRUD actions for Advertcategory model.
 */
class AdvertcategoryController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
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
                       'actions' => ['create', 'update', 'index', 'view', 'delete', 'block', 'position', 'bottom', 'bottomcreate', 'bottomupdate', 'sort_banner', 'loadcategory', 'bottomdelete'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                //    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Advertcategory models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $access = Authitem::AuthitemCheck('2', '24');
        if (yii::$app->user->can($access)) {
            $searchModel = new AdvertcategorySearch();
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
     * Displays a single Advertcategory model.
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
     * Creates a new Advertcategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '24');
        if (yii::$app->user->can($access)) {
            $model = new Advertcategory();
            $model1 = new Image();

            $not_exists = Yii::$app->db->createCommand('SELECT category_id FROM whitebook_advert_category where advert_position!="bottom"');
            $result = $not_exists->queryAll();
            $out1[] = array();
            $out2[] = array();
            foreach ($result as $r) {
                if (is_numeric($r['category_id'])) {
                    $out1[] = $r['category_id'];
                }
                if (!is_numeric($r['category_id'])) {
                    $out2[] = explode(',', $r['category_id']);
                }
            }
            $p = array();
            foreach ($out2 as $id) {
                foreach ($id as $key) {
                    $p[] = $key;
                }
            }
            $k = array();
            if (count($out1)) {
                foreach ($out1 as $o) {
                    if (!empty($o)) {
                        $p[] = $o;
                    }
                }
            }
            $res = "('".implode("','", $p)."')";

            $sql1 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="0" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category_sql1 = Yii::$app->db->createCommand($sql1);
            $cat_id1 = $category_sql1->queryAll();
            $cat_val1[] = 0;
            foreach ($cat_id1 as $key => $val) {
                $cat_val1[] = $val['category_id'];
            }
            $categories1 = Category::find()->where(['category_id' => $cat_val1])->all();
            $category1 = ArrayHelper::map($categories1, 'category_id', 'category_name');
        //Level II
        $sql2 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="1" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category_sql2 = Yii::$app->db->createCommand($sql2);
            $cat_id2 = $category_sql2->queryAll();
            $cat_val2[] = 0;
            foreach ($cat_id2 as $key => $val) {
                $cat_val2[] = $val['category_id'];
            }
            $categories2 = Category::find()->where(['category_id' => $cat_val2])->all();
            $category2 = ArrayHelper::map($categories2, 'category_id', 'category_name');
        // Level III
         $sql3 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="2" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category_sql3 = Yii::$app->db->createCommand($sql3);
            $cat_id3 = $category_sql3->queryAll();
            $cat_val3[] = 0;
            foreach ($cat_id3 as $key => $val) {
                $cat_val3[] = $val['category_id'];
            }
            $categories3 = Category::find()->where(['category_id' => $cat_val3])->all();
            $category3 = ArrayHelper::map($categories3, 'category_id', 'category_name');
            $category['category I'] = $category1;
            $category['category II'] = $category2;
            $category['category III'] = $category3;
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                $vendor = Yii::$app->request->post('Advertcategory');
                $k = array_unique($vendor['category_id']);
                $model->category_id = implode(',', $k);

                $max_sort = $model->findBysql("SELECT MAX(`sort`) as sort FROM `whitebook_advert_category` where advert_position='top'")->asArray()->all();
                $sort = ($max_sort[0]['sort'] + 1);
                $model->sort = $sort;
                $model->save();
                echo Yii::$app->session->setFlash('success', 'New advertisement category created successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model, 'category' => $category,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionBottomcreate()
    {
        $access = Authitem::AuthitemCheck('1', '24');
        if (yii::$app->user->can($access)) {
            $model = new Advertcategory();
            $model1 = new Image();
            $not_exists = Yii::$app->db->createCommand('SELECT category_id FROM whitebook_advert_category where advert_position!="top"');
            $result = $not_exists->queryAll();
            $out1[] = array();
            $out2[] = array();
            foreach ($result as $r) {
                if (is_numeric($r['category_id'])) {
                    $out1[] = $r['category_id'];
                }
                if (!is_numeric($r['category_id'])) {
                    $out2[] = explode(',', $r['category_id']);
                }
            }
            $p = array();
            foreach ($out2 as $id) {
                foreach ($id as $key) {
                    $p[] = $key;
                }
            }
            $k = array();
            if (count($out1)) {
                foreach ($out1 as $o) {
                    if (!empty($o)) {
                        $p[] = $o;
                    }
                }
            }
            $res = "('".implode("','", $p)."')";

        //level I Starting
         $sql1 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="0" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category = Yii::$app->db->createCommand($sql1);
            $cat_id = $category->queryAll();
            $cat_val[] = 0;
            foreach ($cat_id as $key => $val) {
                $cat_val[] = $val['category_id'];
            }
            $categories = Category::find()->where(['category_id' => $cat_val])->all();
            $category1 = ArrayHelper::map($categories, 'category_id', 'category_name');
        //level I Completed

        //level II Starting
        $sql2 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="1" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category2 = Yii::$app->db->createCommand($sql2);
            $cat_id2 = $category2->queryAll();
            $cat_val2[] = 0;
            foreach ($cat_id2 as $key => $val) {
                $cat_val2[] = $val['category_id'];
            }
            $categories2 = Category::find()->where(['category_id' => $cat_val2])->all();
            $category2 = ArrayHelper::map($categories2, 'category_id', 'category_name');

        //level III Starting
        $sql3 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="2" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category3 = Yii::$app->db->createCommand($sql3);
            $cat_id3 = $category3->queryAll();
            $cat_val3[] = 0;
            foreach ($cat_id3 as $key => $val) {
                $cat_val3[] = $val['category_id'];
            }
            $categories3 = Category::find()->where(['category_id' => $cat_val3])->all();
            $category3 = ArrayHelper::map($categories3, 'category_id', 'category_name');
            $category = '';
            $category['category I'] = $category1;
            $category['category II'] = $category2;
            $category['category III'] = $category3;
            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                $Advertcategory = Yii::$app->request->post('Advertcategory');
                $model->category_id = implode(',', $Advertcategory['category_id']);

                $max_sort = $model->findBysql("SELECT MAX(`sort`) as sort FROM `whitebook_advert_category` where advert_position='bottom'")->asArray()->all();
                $sort = ($max_sort[0]['sort'] + 1);
                $model->sort = $sort;
                $model->save();

                echo Yii::$app->session->setFlash('success', 'Bottom advertisement category created successfully!');

                return $this->redirect(['bottom']);
            } else {
                return $this->render('bottomcreate', [
                'model' => $model, 'category' => $category,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Updates an existing Advertcategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '24');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $model->category_id = explode(',', $model->category_id);
            foreach ($model->category_id as $key => $val) {
                $find = Category::categorylevel($val);
                if ($find == 0) {
                    $first[] = $val;
                }
                if ($find == 1) {
                    $second[] = $val;
                }
                if ($find == 2) {
                    $third[] = $val;
                }
            }
            $model->load(Yii::$app->request->post());
            $base = Yii::$app->basePath;
            $not_exists = Yii::$app->db->createCommand('SELECT category_id FROM whitebook_advert_category where advert_position!="bottom"');
            $result = $not_exists->queryAll();
            $out1[] = array();
            $out2[] = array();
            foreach ($result as $r) {
                if (is_numeric($r['category_id'])) {
                    $out1[] = $r['category_id'];
                //$out2[]=0;
                }
                if (!is_numeric($r['category_id'])) {
                    $out2[] = explode(',', $r['category_id']);
                }
            }
            $p = array();
            foreach ($out2 as $id) {
                foreach ($id as $key) {
                    $p[] = $key;
                }
            }
            $k = array();
            if (count($out1)) {
                foreach ($out1 as $o) {
                    if (!empty($o)) {
                        $p[] = $o;
                    }
                }
            }
            if (!empty($first)) {
                foreach ($first as $f) {
                    if (($key = array_search($f, $p)) !== false) {
                        unset($p[$key]);
                    }
                }
            }
            if (!empty($second)) {
                foreach ($second as $s) {
                    if (($key = array_search($s, $p)) !== false) {
                        unset($p[$key]);
                    }
                }
            }
            if (!empty($third)) {
                foreach ($third as $t) {
                    if (($key = array_search($t, $p)) !== false) {
                        unset($p[$key]);
                    }
                }
            }
            $res = "('".implode("','", $p)."')";
            $sql = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="0" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
                //Level I
        $category_sql1 = Yii::$app->db->createCommand($sql);
            $cat_id1 = $category_sql1->queryAll();
            $cat_val1[] = 0;
            foreach ($cat_id1 as $key => $val) {
                $cat_val1[] = $val['category_id'];
            }
            $categories1 = Category::find()->where(['category_id' => $cat_val1])->all();
            $category1 = ArrayHelper::map($categories1, 'category_id', 'category_name');
        //Level II
        $sql2 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="1" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category_sql2 = Yii::$app->db->createCommand($sql2);
            $cat_id2 = $category_sql2->queryAll();
            $cat_val2[] = 0;
            foreach ($cat_id2 as $key => $val) {
                $cat_val2[] = $val['category_id'];
            }
            foreach ($model->category_id as $key => $val) {
                $cat_val2[] = $val;
            }
            $categories2 = Category::find()->where(['category_id' => $cat_val2])->all();
            $category2 = ArrayHelper::map($categories2, 'category_id', 'category_name');
        // Level III
        $sql3 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="2" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category_sql3 = Yii::$app->db->createCommand($sql3);
            $cat_id3 = $category_sql3->queryAll();
            $cat_val3[] = 0;
            foreach ($cat_id3 as $key => $val) {
                $cat_val3[] = $val['category_id'];
            }
            $categories3 = Category::find()->where(['category_id' => $cat_val3])->all();
            $category3 = ArrayHelper::map($categories3, 'category_id', 'category_name');
            $category['category I'] = $category1;
            $category['category II'] = $category2;
            $category['category III'] = $category3;

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $Advertcategory = Yii::$app->request->post('Advertcategory');
                $k = array_unique($Advertcategory['category_id']);
                $model->category_id = implode(',', $k);
                $model->save();
                echo Yii::$app->session->setFlash('success', 'Advertisement category update successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                'model' => $model, 'category' => $category,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionBottomupdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '24');
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $cat = explode(',', $model->category_id);
            $model->category_id = explode(',', $model->category_id);
            foreach ($model->category_id as $key => $val) {
                $find = Category::categorylevel($val);
                if ($find == 0) {
                    $first[] = $val;
                }
                if ($find == 1) {
                    $second[] = $val;
                }
                if ($find == 2) {
                    $third[] = $val;
                }
            }
            $model->load(Yii::$app->request->post());
        //$category1 = Category::loadcategoryset($model->category_id);
                $not_exists = Yii::$app->db->createCommand('SELECT category_id FROM whitebook_advert_category where advert_position!="top"');
            $result = $not_exists->queryAll();
            $out1[] = array();
            $out2[] = array();
            foreach ($result as $r) {
                if (is_numeric($r['category_id'])) {
                    $out1[] = $r['category_id'];
                }
                if (!is_numeric($r['category_id'])) {
                    $out2[] = explode(',', $r['category_id']);
                }
            }
            $p = array();
            foreach ($out2 as $id) {
                foreach ($id as $key) {
                    $p[] = $key;
                }
            }
            $k = array();
            if (count($out1)) {
                foreach ($out1 as $o) {
                    if (!empty($o)) {
                        $p[] = $o;
                    }
                }
            }
            if (!empty($first)) {
                foreach ($first as $f) {
                    if (($key = array_search($f, $p)) !== false) {
                        unset($p[$key]);
                    }
                }
            }
            if (!empty($second)) {
                foreach ($second as $s) {
                    if (($key = array_search($s, $p)) !== false) {
                        unset($p[$key]);
                    }
                }
            }
            if (!empty($third)) {
                foreach ($third as $t) {
                    if (($key = array_search($t, $p)) !== false) {
                        unset($p[$key]);
                    }
                }
            }
            $res = "('".implode("','", $p)."')";
        //level I Starting
         $sql1 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="0" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category = Yii::$app->db->createCommand($sql1);
            $cat_id = $category->queryAll();
            $cat_val[] = 0;
            foreach ($cat_id as $key => $val) {
                $cat_val[] = $val['category_id'];
            }
            $categories = Category::find()->where(['category_id' => $cat_val])->all();
            $category1 = ArrayHelper::map($categories, 'category_id', 'category_name');
        //level I Completed

        //level II Starting
        $sql2 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="1" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category2 = Yii::$app->db->createCommand($sql2);
            $cat_id2 = $category2->queryAll();
            $cat_val2[] = 0;
            foreach ($cat_id2 as $key => $val) {
                $cat_val2[] = $val['category_id'];
            }
            $categories2 = Category::find()->where(['category_id' => $cat_val2])->all();
            $category2 = ArrayHelper::map($categories2, 'category_id', 'category_name');
        //level III Starting
        $sql3 = 'SELECT category_id,category_name FROM whitebook_category where  whitebook_category.category_allow_sale="Yes" and whitebook_category.category_level="2" and  whitebook_category.trash = "Default"
         and category_id NOT IN '.$res;
            $category3 = Yii::$app->db->createCommand($sql3);
            $cat_id3 = $category3->queryAll();
            $cat_val3[] = 0;
            foreach ($cat_id3 as $key => $val) {
                $cat_val3[] = $val['category_id'];
            }
            $categories3 = Category::find()->where(['category_id' => $cat_val3])->all();
            $category3 = ArrayHelper::map($categories3, 'category_id', 'category_name');
            $category = '';
            $category['category I'] = $category1;
            $category['category II'] = $category2;
            $category['category III'] = $category3;

            if ($model->load(Yii::$app->request->post()) && ($model->validate())) {
                $Advertcategory = Yii::$app->request->post('Advertcategory');
                $model->category_id = implode(',', $Advertcategory['category_id']);
                $model->save();
                echo Yii::$app->session->setFlash('success', 'Bottom advertisement category updated successfully!');

                return $this->redirect(['bottom']);
            } else {
                return $this->render('bottomupdate', [
                'model' => $model, 'category' => $category,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Deletes an existing Advertcategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '14');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();
            echo Yii::$app->session->setFlash('success', 'Top category ads deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }
    public function actionBottomdelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '14');
        if (yii::$app->user->can($access)) {
            $this->findModel($id)->delete();
            echo Yii::$app->session->setFlash('success', 'Bottom category ads deleted successfully!');

            return $this->redirect(['bottom']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
     * Finds the Advertcategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Advertcategory the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advertcategory::findOne($id)) !== null) {
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
        $status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
        $command = \Yii::$app->db->createCommand('UPDATE whitebook_advert_category SET status="'.$status.'" WHERE advert_id='.$data['aid']);
        $command->execute();
        if ($status == 'Active') {
            return \Yii::$app->params['appImageUrl'].'active.png';
        } else {
            return \Yii::$app->params['appImageUrl'].'inactive.png';
        }
    }

    public function actionPosition()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $categories = Advertcategory::find()->select('advert_position')->where(['category_id' => $data['cat_id']])->one();

        if (empty($categories)) {
            echo '<option value="top">Top</option><option value="bottom">Bottom</option>';
        } elseif ($categories['advert_position'] == 'top') {
            echo '<option value="bottom">Bottom</option>';
        } elseif ($categories['advert_position'] == 'bottom') {
            echo '<option value="top">Top</option>';
        }
    }

    public function actionBottom()
    {
        $access = Authitem::AuthitemCheck('2', '24');
        if (yii::$app->user->can($access)) {
            $searchModel = new AdvertcategorySearch();
            $dataProvider = $searchModel->bottomsearch(Yii::$app->request->queryParams);

            return $this->render('bottomindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    public function actionSort_banner()
    {
        $sort = $_POST['sort_val'];
        $advert_id = $_POST['advert_id'];
        $command = \Yii::$app->DB->createCommand(
        'UPDATE whitebook_advert_category SET sort="'.$sort.'" WHERE advert_id='.$advert_id);
        if ($command->execute()) {
            Yii::$app->session->setFlash('success', 'Ads category sort order updated successfully!');
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }
}
