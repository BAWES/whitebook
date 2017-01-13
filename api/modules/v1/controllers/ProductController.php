<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\db\Expression;
use frontend\models\Vendor;
use common\models\CategoryPath;
use common\models\VendorItem;
use api\models\EventItemlink;

/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class ProductController extends Controller
{
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


    /**
     * @return array
     */
    public function actionCategoryProducts(
        $categoryId,
        $forSale,
        $requestedLocation,
        $requestedDeliverDate,
        $requestedPrice,
        array $requestedCategories,
        array $requestedVendor,
        array $requestedTheme
    )
    {
        $offset = 0;
        $limit = 10;

        if (isset($requestedVendor) && $requestedVendor != '') {
            $arr_vendor_slugs = $requestedVendor;
        }else{
            $arr_vendor_slugs = [];
        }

        $ActiveVendors = Vendor::loadvalidvendorids(
            false, //current category
            $arr_vendor_slugs, //only selected from filter
            '', //who available today
            ''//delivery on location available
        );

        $item_query = CategoryPath::find()
            ->select('{{%vendor_item}}.item_for_sale, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar')
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
                '{{%vendor_item}}.item_status' => 'Active',
            ]);

        if (isset($forSale) && $forSale != '') {
            $item_query->andWhere(['{{%vendor_item}}.item_for_sale' => 'Yes']);
        }

        $item_query->andWhere(['in', '{{%vendor_item}}.vendor_id', $ActiveVendors]);

//        //price filter
        if (isset($requestedPrice) && $requestedPrice != '') {

            $price_condition = [];

            $arr_min_max = explode('-', $requestedPrice);

            $price_condition[] = '{{%vendor_item}}.item_price_per_unit IS NULL';
            $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1];

            $item_query->andWhere(implode(' OR ', $price_condition));
        }

//        //theme filter
        if (isset($requestedTheme) && count($requestedTheme)>0) {

            $item_query->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id');
            $item_query->leftJoin('{{%theme}}', '{{%theme}}.theme_id = {{%vendor_item_theme}}.theme_id');
            $item_query->andWhere(['IN', '{{%theme}}.slug', $requestedTheme]);

        }//if themes
//
        //category filter
        $cats = '';

        if($categoryId)
        {
            $cats = $categoryId;
        }

        if (isset($requestedCategories) && count($requestedCategories) > 0)
        {
            $cats = implode("','",  $requestedCategories);
        }

        if ($categoryId != "all") {
            $q = "{{%category_path}}.path_id IN ('" . $cats . "')";
            $item_query->andWhere($q);
        }

        if ($requestedLocation) {
            $location = $requestedLocation;
            $item_query->andWhere('EXISTS (SELECT 1 FROM {{%vendor_location}} WHERE {{%vendor_location}}.area_id="'.$location.'" AND {{%vendor_item}}.vendor_id = {{%vendor_location}}.vendor_id)');
        }

        if ($requestedDeliverDate) {
            $date = date('Y-m-d', strtotime($requestedDeliverDate));
            $condition = " ({{%vendor}}.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '".$date."')) ";
            $item_query->andWhere($condition);
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

        return $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy($expression)
            ->asArray()
            ->offset($offset)
            ->limit($limit)
            ->all();
    }

    /*
     * Method to view product detail
     */
    public function actionProductDetail($product_id)
    {
        $productData = VendorItem::findOne($product_id);
        return ($productData) ? $productData : [];
    }

    /*
     * Method to add item in event
     */
    public function actionAddProductToEvent()
    {
        $item_id = Yii::$app->request->getBodyParam("item_id");
        $event_id = Yii::$app->request->getBodyParam("event_id");
        $event_name = Yii::$app->request->getBodyParam("event_name");
        if ($event_id && $item_id) {

            $model = new \frontend\models\Users();
            $customer_id = 182;
            $item = VendorItem::findOne($item_id);
            $item_name = $item->item_name;

            $check = EventItemlink::find()->select('link_id')
                ->where(['event_id'=>$event_id])
                ->andWhere(['item_id'=>$item_id])
                ->count();
            if ($check > 0) {
                return [
                    "operation" => "error",
                    'message' => Yii::t('frontend', '{item_name} already exist with {event_name}',
                        [
                            'item_name' => $item_name,
                            'event_name' => $event_name,
                        ])
                ];
            } else {
                $event_date = date('Y-m-d H:i:s');
                $command = new EventItemlink();
                $command->event_id = $event_id;
                $command->item_id = $item_id;
                $command->link_datetime = $event_date;
                $command->created_datetime = $event_date;
                $command->modified_datetime = $event_date;
                $command->modified_by = $customer_id;
                if($command->save())
                {
                    return [
                        "operation" => "success",
                        'message' => Yii::t('frontend', '{item_name} has been added to {event_name}',
                            [
                                'item_name' => $item_name,
                                'event_name' => $event_name,
                            ])
                    ];
                }
            }
        }
    }
}
