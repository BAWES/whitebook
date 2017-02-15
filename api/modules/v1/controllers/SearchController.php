<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Expression;
use yii\data\ArrayDataProvider;
use frontend\models\Vendor;
use frontend\models\Themes;
use frontend\models\Users;
use common\models\VendorItem;
use common\models\CategoryPath;
use yii\rest\Controller;
class SearchController extends Controller
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
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options','index'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        // Return Header explaining what options are available for next request
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    public function actionIndex($q)
    {
        $items_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_id,{{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar')
            ->leftJoin(
                '{{%vendor_item_to_category}}',
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            )
            ->leftJoin(
                '{{%vendor_item}}',
                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
            )
            ->leftJoin(
                '{{%priority_item}}',
                '{{%priority_item}}.item_id = {{%vendor_item}}.item_id'
            )
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active'
            ]);

        if ($q != 'All') {
            $items_query->andWhere(['like','{{%vendor_item}}.item_name', $q]);
        }

        $expression = new Expression(
            "CASE
                WHEN
                    `whitebook_priority_item`.priority_level IS NULL
                    OR whitebook_priority_item.status = 'Inactive'
                    OR whitebook_priority_item.trash = 'Deleted'
                    OR DATE(whitebook_priority_item.priority_start_date) > DATE(NOW())
                    OR DATE(whitebook_priority_item.priority_end_date) < DATE(NOW())
                THEN 2
                WHEN `whitebook_priority_item`.priority_level = 'Normal' THEN 1
                WHEN `whitebook_priority_item`.priority_level = 'Super' THEN 0
                ELSE 2
            END, {{%vendor_item}}.sort");

        $item_query_result = $items_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy($expression)
            ->asArray()
            ->all();
        return $item_query_result;
    }
}



