<?php

namespace admin\controllers;

use Yii;
use common\models\VendorDraft;
use common\models\Vendor;
use common\models\VendorPhoneNo;
use common\models\VendorOrderAlertEmails;
use common\models\VendorCategory;
use common\models\VendorDraftPhoneNo;
use common\models\VendorDraftCategory;
use common\models\VendorDraftOrderAlertEmails;
use admin\models\VendorDraftSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * VendorDraftController implements the CRUD actions for VendorDraft model.
 */
class VendorDraftController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all VendorDraft models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorDraftSearch();
        $searchModel->is_ready = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VendorDraft model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $categories = VendorDraftCategory::find()
            ->innerJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_draft_category}}.category_id')
            ->where(['vendor_draft_id' => $id])
            ->all();
        
        $phone_nos = VendorDraftPhoneNo::findAll(['vendor_draft_id' => $id]);
        $emails = VendorDraftOrderAlertEmails::findAll(['vendor_draft_id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'phone_nos' => $phone_nos,
            'categories' => $categories,
            'emails' => $emails
        ]);
    }

    /**
     * Creates a new VendorDraft model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorDraft();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vendor_draft_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VendorDraft model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vendor_draft_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VendorDraft model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VendorDraft model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorDraft the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorDraft::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApprove($id){

        $draft = VendorDraft::findOne($id);

        $attributes = $draft->attributes;

        //unset data from draft to keep data from vendor list 
        //unset($attributes['version']);
        //unset($attributes['sort']);
        
        //copy to vendor from draft 
        $vendor = Vendor::findOne($draft->vendor_id);
        $vendor->scenario = 'vendorprofile';
        $vendor->attributes = $attributes;
        //$item->hide_from_admin = 0;
        $vendor->save(false);

        //vendor phone no 

        VendorPhoneNo::deleteAll(['vendor_id' => $draft->vendor_id]);

        $phone_nos = VendorDraftPhoneNo::findAll(['vendor_draft_id' => $draft->vendor_draft_id]);

        foreach ($phone_nos as $key => $value) {
            $phone = new VendorPhoneNo;
            $phone->vendor_id = $vendor->vendor_id;
            $phone->phone_no = $value->phone_no;
            $phone->type = $value->type;
            $phone->save();   
        }

        //vendor category 

        VendorCategory::deleteAll(['vendor_id' => $draft->vendor_id]);

        $categories = VendorDraftCategory::findAll(['vendor_draft_id' => $draft->vendor_draft_id]);

        foreach ($categories as $key => $value) {
            $category = new VendorCategory;
            $category->category_id = $value->category_id;
            $category->vendor_id = $vendor->vendor_id;
            $category->save();
        }

        // order emails 

        VendorOrderAlertEmails::deleteAll(['vendor_id' => $draft->vendor_id]);

        $emails = VendorDraftOrderAlertEmails::findAll(['vendor_draft_id' => $draft->vendor_draft_id]);

        foreach ($emails as $key => $value) {
            $email = new VendorOrderAlertEmails;
            $email->email_address = $value->email_address;
            $email->vendor_id = $vendor->vendor_id;
            $email->save();
        }

        //remove draft related data 

        VendorDraftPhoneNo::deleteAll(['vendor_draft_id' => $draft->vendor_draft_id]);
        VendorDraftCategory::deleteAll(['vendor_draft_id' => $draft->vendor_draft_id]);
        VendorDraftOrderAlertEmails::deleteAll(['vendor_draft_id' => $draft->vendor_draft_id]);

        //remove from draft 
        
        $draft->delete();

        Yii::$app->session->setFlash('success', 'Draft approved successfully!');

        return $this->redirect(['index']);
    }

    public function actionReject()
    {
        $vendor_draft_id = Yii::$app->request->post('vendor_draft_id');

        $reason = Yii::$app->request->post('reason'); 

        $model = VendorDraft::findOne(['vendor_draft_id' => $vendor_draft_id]);

        $vendor = Vendor::findOne($model->vendor_id);

        //send mail 
        Yii::$app->mailer->htmlLayout = 'layouts/empty';
        
        $mail = Yii::$app->mailer->compose("admin/vendor-draft-reject",
            [
                "reason" => $reason,
                "model" => $model,
                "vendor" => $vendor,
                "image_1" => Url::to("@web/twb-logo-trans.png", true),
                "image_2" => Url::to("@web/twb-logo-horiz-white.png", true)
            ])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
            ->setSubject('Profile update rejected');

        //to contact email  
        $mail 
            ->setTo($vendor->vendor_contact_email)
            ->send();

        //send to all notification mails 
        $vendor_alert_emails = VendorOrderAlertEmails::findAll(['vendor_id' => $vendor->vendor_id]);

        foreach ($vendor_alert_emails as $key => $value) {
            $mail    
                ->setTo($value->email_address)
                ->send();
        }

        //hide draft from admin 
        $model->is_ready = 0;
        $model->save();

        Yii::$app->session->setFlash('success', 'Draft rejected and vendor notified by email!');

        Yii::$app->response->format = 'json';
        
        return [
            'location' => Url::to(['vendor-draft/index'])
        ];
    }
}
