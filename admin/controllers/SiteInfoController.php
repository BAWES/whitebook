<?php

namespace admin\controllers;

use Yii;
use common\models\Siteinfo;
use admin\models\AuthItem;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use admin\models\AccessControlList;

/**
 * SiteinfoController implements the CRUD actions for Siteinfo model.
 */
class SiteInfoController extends Controller
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
     * Updates an existing Siteinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate()
    {
        $data = Siteinfo::find()->all();

        if (Yii::$app->request->isPost) {

            //remove all old data 
            Siteinfo::deleteAll();

            $settings = Yii::$app->request->post();

            foreach ($settings as $key => $value) {
                $info = new Siteinfo();
                $info->name = $key;
                $info->value = $value;
                $info->save();
            }

            Yii::$app->session->setFlash('success', 'Application info updated successfully!');
            Yii::info('[Site] '. Yii::$app->user->identity->admin_name .' updated site information.', __METHOD__);

            return $this->redirect(['update']);
        }

        return $this->render('update', [
            'data' => $data,
        ]);
    }
}
