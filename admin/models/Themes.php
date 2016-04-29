<?php

namespace admin\models;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "whitebook_theme".
 *
 * @property string $theme_id
 * @property string $theme_name
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property VendorItemTheme[] $vendorItemThemes
 * @property VendorItem[] $items
 */
class Themes extends \common\models\Themes
{

  public static function loadthemename()
  {       
      $theme_name= Themes::find()
      ->where(['!=', 'theme_status', 'Deactive'])
      ->andwhere(['!=', 'trash', 'Deleted'])
      ->all();
      $themename=ArrayHelper::map($theme_name,'theme_id','theme_name');
      return $themename;
  } 

  public static function loadthemenameupdate($id)
  {
     $ids = explode(",", $id);
     $id = implode("','", $ids);
     $val = "'".$id."'";
     
     $theme_name =  Themes::find()->where(['theme_id' => [$val]])->all();
     $themename=ArrayHelper::map($theme_name,'theme_id','theme_name');
     return $themename;
  }
}
