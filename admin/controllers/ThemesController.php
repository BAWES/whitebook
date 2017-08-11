<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\Themes;
use admin\models\ThemesSearch;
use admin\models\AuthItem;
use common\models\VendorItemThemes;
use admin\models\AccessControlList;
use admin\models\VendorItemThemesSearch;

/**
 * ThemesController implements the CRUD actions for Themes model.
 */
class ThemesController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
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
                //    'delete' => ['POST'],
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
     * Lists all Themes models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ThemesSearch();
        $searchModel->trash = 'Default';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $themes = Themes::find()
            ->where(['trash' => 'Default'])
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'themes' => $themes
        ]);
    }

    /**
     * Lists all Item in model 
     *
     * @return mixed
     */
    public function actionItems($id)
    {
        $searchModel = new VendorItemThemesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('items', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Themes model.
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
     * Creates a new Themes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Themes();
        $model->scenario = 'insert';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->theme_name = strtolower($model->theme_name);
            $model->save();
            Yii::$app->session->setFlash('success', 'Theme added successfully!');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Themes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->theme_name = strtolower($model->theme_name);
            $model->save();

            Yii::$app->session->setFlash('success', 'Theme updated successfully!');

            return $this->redirect(['index']);
        } else {

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
    * Deletes an existing Themes model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->trash = 'Deleted';
        $model->save(false);

        //delete vendor item theme
        VendorItemThemes::deleteAll(['theme_id' => $id]);

        Yii::$app->session->setFlash('success', 'Theme deleted successfully!');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Themes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Themes the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Themes::findOne($id)) !== null) {
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
		$command=Themes::updateAll(['theme_status' => $status],'theme_id= '.$data['id']);
        if ($status == 'Active') {
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        } else {
            return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
        }
    }

    /**
     * Move all items of old theme to new theme 
     * 
     * @param integer old_theme_id 
     * @param integer new_theme_id 
     */
    public function actionMoveItems()
    {
        $old_theme_id = Yii::$app->request->post('old_theme_id');
        $new_theme_id = Yii::$app->request->post('new_theme_id');

        $items = VendorItemThemes::find()
            ->innerJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id')
            ->where([
                '{{%vendor_item_theme}}.theme_id' => $old_theme_id,
                '{{%vendor_item_theme}}.trash' => 'Default'
            ])
            ->all();

        foreach ($items as $key => $value) 
        {
            //check it already added 

            $count = VendorItemThemes::find()
                ->where([
                    'theme_id' => $new_theme_id,
                    'item_id' => $value->item_id
                ])
                ->count();

            if($count)
                continue;

            $model = new VendorItemThemes;
            $model->item_id = $value->item_id;
            $model->theme_id = $new_theme_id;
            $model->trash = 'Default';
            $model->save();
        }

        VendorItemThemes::deleteAll([
                'theme_id' => $old_theme_id,
                'trash' => 'Default'
            ]);

        $old_theme = Themes::findOne($old_theme_id);

        $new_theme = Themes::findOne($new_theme_id);
            
        Yii::$app->response->format = 'json';

        return [
            'message' => Yii::t('app', 'Items moved from {old_theme} to {new_theme}', [
                    'old_theme' => $old_theme->theme_name,
                    'new_theme' => $new_theme->theme_name
                ])
        ];
    }

    public function actionAssign($id){
        $model = Themes::findOne($id);
        $themes = Themes::find()->active()->all();

        if (!$model) {
            Yii::$app->session->setFlash('danger', 'Invalid Theme');
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isPost) {

            VendorItemThemes::deleteAll(['theme_id'=>$id]);
            $items = Yii::$app->request->post('items');
            foreach($items as $item) {
                $vendorItemTheme = new VendorItemThemes;
                $vendorItemTheme->item_id = $item;
                $vendorItemTheme->theme_id = $id;
                $vendorItemTheme->trash = 'Default';
                $vendorItemTheme->save();
            }
            Yii::$app->session->setFlash('success','Theme item changes.');
            return $this->redirect(['index']);
        }


        return $this->render('assign',['model'=>$model,'allThemes'=>$themes]);
    }
}
