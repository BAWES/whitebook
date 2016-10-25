<?php

namespace frontend\models;
use Yii;
use common\models\VendorItemThemes;

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

  public static function load_all_themename($id, $sort = 'theme_name')
  {
        return $theme_name =  Themes::find()
        ->select(['theme_id','theme_name','theme_name_ar','slug'])
        ->where(['!=', 'theme_status', 'Deactive'])
        ->andWhere(['!=', 'trash', 'Deleted'])
        ->andWhere(['theme_id' => $id])
        ->orderby($sort)
        ->asArray()
        ->all();        
  }


  public static function loadthemename_item($themeData)
  {
    $k=array();
    foreach ($themeData as $data){    
    $k[]=$data;
    }
    $id = implode("','", $k);
    $val = "'".$id."'";
    return $theme =  VendorItemThemes::find()
        ->select(['theme_id'])
        ->where('trash="default" and item_id IN('.$val.')')
         ->asArray()
         ->all();

  }

  public static function loadthemenames($sort = 'theme_name')
  {             
      return $theme_name= Themes::find()
          ->where(['!=', 'theme_status', 'Deactive'])
          ->andwhere(['!=', 'trash', 'Deleted'])
          ->orderby([$sort => SORT_ASC])
          ->asArray()
          ->all();
  }
}
