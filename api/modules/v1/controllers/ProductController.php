<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\db\Expression;
use \common\models\CategoryPath;
use \common\models\VendorItem;
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
    public function actionIndex($id)
    {
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

        $q = "{{%category_path}}.path_id IN ('".$id."')";

        $item_query->andWhere($q);

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

        return $item_query_result = $item_query
            ->groupBy('{{%vendor_item}}.item_id')
            ->orderBy($expression)
            ->asArray()
            ->all();
    }

    public function actionDetail($id)
    {
        return VendorItem::findOne($id);
    }
}
