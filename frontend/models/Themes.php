<?php

namespace frontend\models;
use Yii;
use common\models\Vendoritemthemes;

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

  public static function load_all_themename($id)
  {     
        $theme_name =  Themes::find()
        ->select(['theme_id','theme_name','slug'])
        ->where(['!=', 'theme_status', 'Deactive'])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->andwhere(['theme_id' => $id])->all();
    return $theme_name;
  }


  public static function loadthemename_item($themeData)
  {
    $k=array();
    foreach ($themeData as $data){    
    $k[]=$data;
    }
    $id = implode("','", $k);
    $val = "'".$id."'";
    $theme =  Vendoritemthemes::find()
        ->select(['theme_id'])
        ->andwhere(['!=', 'trash', 'Deleted'])
         ->andwhere(['IN', 'item_id', $val])
         ->all();
    return $theme;die;
  }

  public static function loadthemenames()
  {       
          $theme_name= Themes::find()
          ->where(['!=', 'theme_status', 'Deactive'])
          ->andwhere(['!=', 'trash', 'Deleted'])
          ->asArray()
          ->all();            
          return $theme_name;
  }
}
