<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Category;
use common\models\Authitem;
use common\models\Categoryads;
use common\models\CategoryadsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CategoryadsController implements the CRUD actions for Categoryads model.
 */
class CategoryadsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Categoryads models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoryadsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categoryads model.
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
     * Creates a new Categoryads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categoryads();

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

            //$out1[]=0;
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

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $vendor = Yii::$app->request->post('categoryads');
            $k = array_unique($vendor['category_id']);
            $model->category_id = implode(',', $k);
            $model->save();
            echo Yii::$app->session->setFlash('success', 'New advertisement category created successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model, 'category' => $category,
            ]);
        }
    }

    /**
     * Updates an existing Categoryads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
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
                $Advertcategory = Yii::$app->request->post('categoryads');
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

    /**
     * Deletes an existing Categoryads model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categoryads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Categoryads the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categoryads::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
