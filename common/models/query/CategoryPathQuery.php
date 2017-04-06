<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[CategoryPath]].
 *
 * @see Booking
 */
class CategoryPathQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CategoryPath[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CategoryPath|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function selectedFields()
    {
        return $this->select('{{%vendor_item}}.item_base_price,{{%vendor_item}}.item_status,{{%vendor_item}}.trash,{{%vendor_item}}.item_approved,{{%vendor_item}}.item_how_long_to_make, {{%vendor_item}}.notice_period_type, {{%vendor_item}}.slug, {{%vendor_item}}.item_id, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.item_price_per_unit, {{%vendor}}.vendor_id, {{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar');
    }

    public function categoryJoin()
    {
        return $this->leftJoin('{{%vendor_item_to_category}}', '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id');
    }

    public function itemJoin()
    {
        return $this->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id');
    }

    public function priorityItemJoin()
    {
        return $this->leftJoin('{{%priority_item}}','{{%priority_item}}.item_id = {{%vendor_item}}.item_id');
    }

    public function workingTimeJoin()
    {
        return $this->leftJoin('{{%vendor_working_timing}}', '{{%vendor_working_timing}}.vendor_id = {{%vendor}}.vendor_id');
    }

    public function itemThemeJoin()
    {
        return $this->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id');
    }

    public function vendorJoin()
    {
        return $this->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id');
    }

    public function themeJoin()
    {
        return $this->leftJoin('{{%theme}}', '{{%theme}}.theme_id = {{%vendor_item_theme}}.theme_id');
    }

    public function defaultItems()
    {
        return $this->andWhere(['{{%vendor_item}}.trash' => 'Default']);
    }

    public function approvedItems()
    {
        return $this->andWhere(['{{%vendor_item}}.item_approved' => 'Yes']);
    }

    public function activeItems()
    {
        return $this->andWhere(['{{%vendor_item}}.item_status' => 'Active']);
    }

    public function byVendorIDs($ActiveVendors)
    {
        return $this->andWhere(['in', '{{%vendor_item}}.vendor_id', $ActiveVendors]);
    }

    public function byThemeSlug($themes)
    {
        return $this->andWhere(['IN', '{{%theme}}.slug', $themes]);
    }

    public function byPrice($MinPrice, $MaxPrice)
    {
        $price_condition[] = '{{%vendor_item}}.item_price_per_unit IS NULL';
        $price_condition[] = '{{%vendor_item}}.item_price_per_unit between '.$MinPrice.' and '.$MaxPrice;
        return $this->andWhere(implode(' OR ', $price_condition));
    }

    public function byCategoryIDs($cats)
    {
        return $this->andWhere("{{%category_path}}.path_id IN ('" . $cats . "')");
    }

    public function byDeliveryLocation($location)
    {
        return $this->andWhere('EXISTS (SELECT 1 FROM {{%vendor_location}} WHERE {{%vendor_location}}.area_id="' . $location . '" AND {{%vendor_item}}.vendor_id = {{%vendor_location}}.vendor_id)');
    }

    public function byDeliveryDate($date)
    {
        return $this->andWhere("{{%vendor}}.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '" . $date . "')");
    }

    public function byEventTime($event_time,$working_day)
    {
        return $this->andWhere("'" . $event_time . "' >= {{%vendor_working_timing}}.working_start_time AND '" . $event_time . "' < {{%vendor_working_timing}}.working_end_time AND working_day='" . $working_day . "day'");
    }

    public function orderByExpression()
    {
        $expression = new \yii\db\Expression(
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

        return $this->orderBy($expression);
    }
}