<?php

namespace frontend\models;

use yii\base\Model;
use Yii;

class CategoryModel extends Model
{
    public function get_products_based_category($category = '', $limit, $offset)
    {
        $today = date('Y-m-d H:i:s');
        return $result = Vendor::find()
			->select(['{{%vendor_item}}.item_price_per_unit','{{%vendor_item}}.item_id','{{%vendor_item}}.item_name','{{%vendor_item}}.item_description','{{%vendor}}.vendor_name','{{%category}}.category_id'])
			->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
			->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
			
			->Where(['{{%vendor_item}}.trash' => 'Default','{{%vendor_item}}.item_approved' => 'yes','{{%vendor_item}}.item_archived' => 'no','{{%vendor_item}}.item_status' => 'Active','{{%vendor_item}}.item_for_sale' => 'Yes','{{%image}}.module_type' => 'vendor_item',
			'{{%vendor}}.vendor_Status' => 'Active',
			'{{%category}}.trash' => 'Default',
			'{{%category}}.approve_status' => 'yes',
			'{{%category}}.category_id' => $cat_id,
			'{{%category}}.trash' => 'Default',
			'{{%category}}.category_allow_sale' => 'yes'])
			->andwhere(['<=','package_start_date',$today])
			->andwhere(['>=','package_end_date',$today])
			->asArray()
			->all();
    }

    // get the category id based oin the category name
    public function get_category_id($category = '')
    {
		return $category = \common\models\Category::find()
			->select(['category_id','category_name'])
			->Where(['trash'=>'default'])
			->andWhere(['category_allow_sale'=>'yes'])
			->andWhere(['category_url'=>$category])
			->asArray()
			->all();
    }

    public function get_main_category()
    {
		return $theme = \common\models\Category::find()
			->select(['category_id','category_name','category_url'])
			->Where(['parent_category_id'=>null])
			->andWhere(['trash'=>'default'])
			->andWhere(['category_allow_sale'=>'yes'])
			->asArray()
			->all();
        return $general;
    }


    public function get_themes()
    {
		 return $theme = \common\models\themes::find()
			->select(['theme_id','theme_name'])
			->Where(['theme_status'=>'Active'])
			->andWhere(['trash'=>'default'])
			->asArray()
			->all();
    }

    public function vendor_list()
    {
        $today = date('Y-m-d H:i:s');
        return $vendor = \admin\models\Vendor::find()
			->select(['vendor_id','vendor_name'])
			->Where(['vendor_status'=>'Active'])
			->andWhere(['trash'=>'default'])
			->andWhere(['approve_status'=>'Yes'])
			->andWhere(['<=','package_start_date',$today])
			->andWhere(['>=','package_end_date',$today])
			->orderBy(['vendor_name'=>ASC])
			->asArray()
			->all();
        return $vendor;
    }

    public function get_event_types()
    {
		$events = \common\models\Eventtype::find()
			->select(['type_name','type_id'])
			->asArray()
			->all();
        return $events;
    }
}
