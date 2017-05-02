<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use common\models\Package;
use common\models\VendorItemToPackage;

/**
 * Package controller 
 */
class PackageController extends Controller
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
            'list',
            'view'
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
     * Return a List of Packages
     */
    public function actionList()
    {
        $query = Package::find()
            ->active();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return package detail 
     */
    public function actionView($id)
    {
        $package = Package::find()
            ->where([
                'package_id' => $id,
                'status' => 1
            ])
            ->asArray()
            ->one();        

        if(!$package) 
        {
            return [
                "operation" => "error",
                "message" => "Package not found",
            ];
        }

        $items = VendorItemToPackage::find()
            ->select(['{{%vendor}}.vendor_name', '{{%vendor}}.vendor_name_ar', '{{%vendor_item}}.item_name','{{%vendor_item}}.item_name_ar','{{%vendor_item}}.item_id','{{%vendor_item}}.item_price_per_unit','{{%vendor_item}}.notice_period_type','{{%vendor_item}}.item_how_long_to_make','{{%vendor_item}}.item_base_price','{{%vendor_item}}.min_order_amount'])
            ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%vendor_item_to_package}}.item_id')
            ->leftJoin(
                '{{%vendor_item_to_category}}', 
                '{{%vendor_item_to_category}}.item_id = {{%vendor_item}}.item_id'
            )
            ->leftJoin(
                '{{%category_path}}', 
                '{{%category_path}}.category_id = {{%vendor_item_to_category}}.category_id'
            )
            ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
            ->where([
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item_to_package}}.package_id' => $package['package_id']
            ])
            ->groupBy('{{%vendor_item_to_package}}.item_id')
            ->asArray()
            ->all();

        if ($items)
        {
            $customData = ['image'=>'','notice'=>''];
            foreach ($items as $item)
            {
                $image = \common\models\Image::find()
                    ->where(['item_id' => $item['item_id']])
                    ->orderBy(['vendorimage_sort_order' => SORT_ASC])
                    ->one();

                if ($image) {
                    $customData['image'] = $image->image_path;
                } else {
                    $customData['image'] = '';
                }

                // for notice period
                $value = $item;
                $notice = '';
                if (isset($value['item_how_long_to_make']) && $value['item_how_long_to_make'] > 0) {
                    if (isset($value['notice_period_type']) && $value['notice_period_type'] == 'Day') {
                        if ($value['item_how_long_to_make'] >= 7) {
                            $notice = Yii::t('frontend', '{count} week(s)', [
                                'count' => substr(($value['item_how_long_to_make'] / 7), 0, 3)
                            ]);
                        } else {
                            $notice = Yii::t('frontend', '{count} day(s)', [
                                'count' => $value['item_how_long_to_make']
                            ]);
                        }
                    } else {
                        if ($value['item_how_long_to_make'] >= 24) {
                            $notice = Yii::t('frontend', '{count} day(s)', [
                                'count' => substr(($value['item_how_long_to_make'] / 24), 0, 3)
                            ]);
                        } else {
                            $notice = Yii::t('frontend', '{count} hours', [
                                'count' => $value['item_how_long_to_make']
                            ]);
                        }
                    }
                }
                $customData['notice'] = $notice;

                $products[] = $item + $customData;
            }
        }

        return [
            'package'=>$package,
            'products'=>$products
        ];
    }
}