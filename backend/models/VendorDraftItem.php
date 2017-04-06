<?php

namespace backend\models;

use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\VendorItemPricing;
use common\models\Image;
use common\models\VendorItemToCategory;
use common\models\VendorDraftItemToCategory;
use common\models\VendorItemMenu;
use common\models\VendorDraftImage;
use common\models\VendorDraftItemPricing;
use common\models\VendorItemMenuItem;
use common\models\VendorDraftItemMenu;
use common\models\VendorDraftItemMenuItem;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_draft_item".
 *
 * @property integer $draft_item_id
 * @property string $item_id
 * @property string $type_id
 * @property string $vendor_id
 * @property string $item_name
 * @property string $item_name_ar
 * @property string $priority
 * @property string $item_description
 * @property string $item_description_ar
 * @property string $item_additional_info
 * @property string $item_additional_info_ar
 * @property integer $item_default_capacity
 * @property string $item_price_per_unit
 * @property string $item_customization_description
 * @property string $item_customization_description_ar
 * @property string $item_price_description
 * @property string $item_price_description_ar
 * @property string $item_base_price
 * @property integer $sort
 * @property integer $item_how_long_to_make
 * @property integer $item_minimum_quantity_to_order
 * @property string $item_archived
 * @property string $item_approved
 * @property string $item_status
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 * @property string $slug
 *
 * @property Vendor $vendor
 * @property ItemType $type
 */
class VendorDraftItem extends \common\models\VendorDraftItem
{
    public function rules()
    {
        return [

            //MenuItems

            [['allow_special_request', 'have_female_service'], 'number', 'on' => ['MenuItems']],

            //ItemPrice

            [['quantity_label', 'item_price_description','item_price_description_ar', 'item_customization_description', 'item_customization_description_ar'], 'string', 'on' => ['ItemPrice']],

            [['item_default_capacity', 'item_minimum_quantity_to_order'], 'integer', 'on' => ['ItemPrice']],

            [['min_order_amount', 'item_price_per_unit','item_base_price'], 'number', 'on' => ['ItemPrice']],

            [['type_id', 'minimum_increment'], 'integer', 'on' => ['ItemPrice']],

            [['type_id','item_price_per_unit'], 'required', 'on' => ['ItemPrice']],

            //ItemDescription

            [['set_up_time', 'set_up_time_ar', 'max_time', 'max_time_ar', 'requirements','requirements_ar', 'item_how_long_to_make', 'notice_period_type', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'], 'string', 'on' => ['ItemDescription']],

            //ItemInfo

            [['item_name', 'item_name_ar'], 'required', 'on' => ['ItemInfo']]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['MenuItems'] = ['allow_special_request', 'have_female_service'];

        $scenarios['ItemPrice'] = ['type_id','minimum_increment', 'min_order_amount', 'quantity_label', 'item_default_capacity', 'item_minimum_quantity_to_order', 'item_price_per_unit', 'item_base_price', 'item_price_description', 'item_price_description_ar', 'item_customization_description', 'item_customization_description_ar'];

        $scenarios['ItemDescription'] = ['set_up_time', 'set_up_time_ar', 'max_time', 'max_time_ar', 'requirements', 'requirements_ar', 'item_how_long_to_make', 'notice_period_type', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'];

        $scenarios['ItemInfo'] = ['item_name', 'item_name_ar', 'item_status'];

        return $scenarios;
    }

    public function create_from_item($id) {

        $model = VendorItem::findOne(['item_id' => $id, 'vendor_id'=>Yii::$app->user->getId()]);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $draft = new \common\models\VendorDraftItem();
        $draft->attributes = $model->attributes;
        $draft->item_approved = 'Pending';
        $draft->save();

        //copy draft related data

        $pricing = VendorItemPricing::loadpricevalues($model->item_id);

        foreach ($pricing as $key => $value) {
            $vdip = new VendorDraftItemPricing;
            $vdip->attributes = $value->attributes;
            $vdip->save();
        }

        $images = Image::findAll(['item_id' => $model->item_id]);

        foreach ($images as $key => $value) {
            $vdi = new VendorDraftImage;
            $vdi->attributes = $value->attributes;
            $vdi->save();
        }

        $categories = VendorItemToCategory::findAll(['item_id' => $model->item_id]);

        foreach ($categories as $key => $value) {
            $dic = new VendorDraftItemToCategory;
            $dic->attributes = $value->attributes;
            $dic->save();
        }

        //menu
        $menues = VendorItemMenu::findAll(['item_id' => $model->item_id]);

        foreach ($menues as $key => $menu) {

            $dm = new VendorDraftItemMenu;
            $dm->attributes = $menu->attributes;
            $dm->save();

            $menu_items = VendorItemMenuItem::findAll(['menu_id' => $menu->menu_id]);

            foreach ($menu_items as $key => $menu_item) {
                $dmi = new VendorDraftItemMenuItem;
                $dmi->attributes = $menu_item->attributes;
                $dmi->draft_menu_id = $dm->draft_menu_id;
                $dmi->save();
            }
        }

        return $draft;
    }
}
