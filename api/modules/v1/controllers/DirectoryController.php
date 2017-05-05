<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use api\models\Vendor;

/**
 * Directory controller 
 */
class DirectoryController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'options',
            'list'
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * Return a List of Directory
     */
    public function actionList()
    {
        if(Yii::$app->language == "en") {
            $sort = 'vendor_name';
        } else {
            $sort = 'vendor_name_ar';
        }

        $today = date('Y-m-d H:i:s');

        $query = Vendor::find()
            ->defaultVendor()
            ->active()
            ->approved();

        $vendors = $query->orderby(['{{%vendor}}.'.$sort => SORT_ASC])
            ->groupby(['{{%vendor}}.vendor_id'])
            ->all();

        $result = [];

        foreach ($vendors as $key => $value) 
        {
            if(Yii::$app->language == "en") {
                $firstLetter = strtoupper(mb_substr($value['vendor_name'], 0, 1, 'utf8'));
            }else{
                $firstLetter = mb_substr($value['vendor_name_ar'], 0, 1, 'utf8');
            }

            $result[$firstLetter][] = $value;
        }

        return [
            'directory' => $result,
            'keys' => array_keys($result)
        ];
    }
}