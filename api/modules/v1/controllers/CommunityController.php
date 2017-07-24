<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use api\models\Vendor;
use common\models\CategoryPath;

/**
 * Community controller 
 */
class CommunityController extends Controller
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
            'community' => $result,
            'keys' => array_keys($result)
        ];
    }
    /**
     * @param int $offset
     * @param $vendor_id
     * @return array
     */
    public function actionView ($offset = 0, $vendor_id = 0)
    {
        $products = [];
        
        $limit = Yii::$app->params['limit'];

        $item_query = CategoryPath::find()
            ->selectedFields()
            ->categoryJoin()
            ->itemJoin()
            ->priorityItemJoin()
            ->vendorJoin()
            ->defaultItems()
            ->approved()
            ->active();
        
        if($vendor_id)
        {
            $item_query->vendorIDs([$vendor_id]);
        }

        $item_query_result = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderByExpression()
            ->asArray()
            ->offset($offset)
            ->limit($limit)
            ->all();

        if ($item_query_result) 
        {
            $customData = ['image'=>'','notice'=>''];
            foreach ($item_query_result as $item) 
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

        return $products;
    }
}