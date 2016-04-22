<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use Yii;
use yii\behaviors\SluggableBehavior;

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
class Themes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_theme';
    }

    public function behaviors()
      {
          return [
              [
                  'class' => SluggableBehavior::className(),
                  'attribute' => 'theme_name',                 
              ],
          ];
      }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			['theme_name','themevalidation','on' => 'insert',],
            [['theme_name'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
            [['theme_name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'theme_id' => 'Theme ID',
            'theme_meta_keywords' => 'Keywords',
            'theme_meta_desc' => 'Description',
            'theme_meta_title' => 'Title',
            'theme_name' => 'Theme Name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorItemThemes()
    {
        return $this->hasMany(VendorItemTheme::className(), ['theme_id' => 'theme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(VendorItem::className(), ['item_id' => 'item_id'])->viaTable('whitebook_vendor_item_theme', ['theme_id' => 'theme_id']);
    }
    
    public static function statusImageurl($img_status)
	{			
		if($img_status == 'Active')		
		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
	}
	
	public  function themevalidation($attribute_name,$params)
	{
		if(!empty($this->theme_name) ){
		$model = Themes::find()
		->where(['theme_name'=>$this->theme_name])->one();
        if($model){
        $this->addError('theme_name','Please enter a unique theme name');
        }
		}
	}
	
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

    // load themes front-end plan page 
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
