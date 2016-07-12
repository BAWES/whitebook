<?php

namespace backend\controllers;

use Yii;
use common\models\Vendorlocation;
use common\models\Location;
use common\models\VendorlocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Country;
use common\models\City;
use yii\filters\AccessControl;

/**
 * VendorlocationController implements the CRUD actions for vendorlocation model.
 */
class VendorlocationController extends Controller
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
    
    public function actionEdit()
    {
        $model= new Vendorlocation;

        if ($model->load(Yii::$app->request->post())) {

            $location = Yii::$app->request->post('location');

            if($location) {
            
                Vendorlocation::deleteAll('vendor_id = :vendor_id', [':vendor_id' => Yii::$app->user->getId()]);
            
                foreach ($location as $key => $value) {
                    $get_city_id = Location::find()->select('city_id')->where(['id'=>$value])->asArray()->one();
                    $vendor_location_tbl = new Vendorlocation;
                    $vendor_location_tbl->vendor_id = Yii::$app->user->getId();
                    $vendor_location_tbl->city_id = $get_city_id['city_id'];
                    $vendor_location_tbl->area_id = $value;
                    $vendor_location_tbl->validate();
                    $vendor_location_tbl->save();
                }

                $model->save();

            } else {
                Vendorlocation::deleteAll('vendor_id = :vendor_id', [':vendor_id' => Yii::$app->user->getId()]);
            }
            
            Yii::$app->session->setFlash('success', "Area info updated successfully!");
            
            return $this->redirect(['edit']);
        }

        Vendorlocation::deleteAll('vendor_id = :vendor_id', [':vendor_id' => 0]); // this is dummy record
        
    	$cities = City::find()->select(['{{%city}}.*'])
    		->leftJoin('{{%location}}', '{{%location}}.city_id = {{%city}}.city_id')
    		->where(['{{%city}}.status'=>'Active'])
    		->andwhere(['{{%location}}.trash'=>'Default'])
    		->andwhere(['{{%location}}.status'=>'Active'])
    		->groupby(['{{%location}}.city_id'])
    		->asArray()
    		->all();

        return $this->render('edit', [
            'model' => $model, 
            'cities' => $cities,
        ]);
    }

    /**
     * Deletes an existing vendorlocation model.
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
     * Finds the vendorlocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return vendorlocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = vendorlocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
