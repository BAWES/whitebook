<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "{{%cms}}".
 *
 * @property integer $page_id
 * @property string $page_name
 * @property string $page_content
 * @property integer $page_order
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Cms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms}}';
    }
    
	public function behaviors()
	{
	      return [
	          [
	              'class' => SluggableBehavior::className(),
	              'attribute' => 'page_name',	
	              
            'immutable' => false,
            
            'ensureUnique'=>true,
	              'value' => function ($event) {
				 return str_replace(' ', '-', $this->slug);
        },             
	          ],
	      ];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_name', 'page_content', 'cms_meta_title', 'cms_meta_keywords', 'cms_meta_description'], 'required'],
            [['page_content','page_status', 'trash'], 'string'],
            [['page_order', 'created_by', 'modified_by'], 'integer'],
            [['page_order', 'created_by', 'modified_by',], 'safe'],
            [['page_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'page_name' => 'Page Name',
            'page_content' => 'Page Content',
            'page_order' => 'Page Order',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'cms_meta_title' => 'Page meta title',
            'cms_meta_keywords' => 'Page meta keywords',
            'cms_meta_description' => 'Page meta description',
        ];
    }
    
    	public static function getContent($id)
	{
		$content=Cms::find()
		->select(['page_content'])
		->where(['page_id' => $id])
		->all();
		 $k=($content[0]['page_content']);
		 return   substr($k, 0, 150);
		
	}
    	public static function cms_details($slug)
	{
		//echo $slug;die;
		if($slug){
		$content=Cms::find()
		->select(['page_id','page_content','page_name','cms_meta_title','cms_meta_keywords','cms_meta_description'])
		->where(['slug' => $slug])
		->andwhere(['page_status' => 'active'])
		->andwhere(['trash' => 'Default'])
		->one();
		return ($content);
		}
				
	}    
    
    public static function content($content)
	{       
			return strip_tags($content); 
	}	
}
