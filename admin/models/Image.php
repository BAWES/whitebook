<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "whitebook_image".
 *
 * @property string $image_id
 * @property string $image_user_id
 * @property string $image_user_type
 * @property string $image_path
 * @property string $image_file_size
 * @property string $image_width
 * @property string $image_height
 * @property string $image_datetime
 * @property string $image_ip_address
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property VendorItemImage[] $vendorItemImages
 * @property VendorItemQuestionAnswerOption[] $vendorItemQuestionAnswerOptions
 * @property VendorItemQuestionGuide[] $vendorItemQuestionGuides
 */
class Image extends \common\models\Image
{

    // Only for Item type sales
    public static function loadimageids($image_id)
    {
        $model = Image::find()->where(['image_id'=>$image_id,'module_type'=>'sales_guides'])->all();
        return $model;
    }

    // Delete item images
    public static function loadguideimageids($image_id)
    {
        $model = Image::find()->where(['image_id'=>$image_id,'module_type'=>'vendor_item'])->one();
        return $model['image_path'];
    }

    
   /* 
    *
    *   To save created, modified user & date time 
    */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
           $this->created_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->created_by = \Yii::$app->user->identity->id;
        } 
        else {
           $this->modified_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->modified_by = \Yii::$app->user->identity->id;
        }
           return parent::beforeSave($insert);
    }
}
