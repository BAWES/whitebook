<?php

namespace admin\models;
use Yii;
use yii\helpers\Url;
use yii\validators\Validator;
/**
 * This is the model class for table "{{%advert_category}}".
 *
 * @property string $advert_id
 * @property string $category_id
 * @property string $advert_position
 * @property string $advert_code
 *
 * @property Category $category
 */
class Advertcategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $category_type;
    public static function tableName()
    {
        return '{{%advert_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'advert_position', 'advert_code'], 'required'],
            [['advert_position', 'advert_code'], 'string'],
            [['sort'], 'integer'],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advert_id' => 'Advertisement ID',
            'category_id' => 'Category Name',
            'advert_position' => 'Advertisement Position',
            'ads_type' => 'Advertisement Type',
            'advert_code' => 'Advertisement Code',
            'ads_image' => 'Advertisement Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public static function statusImageurl($status)
	{
		if($status == 'Unblock')
		return \Yii::$app->params['appImageUrl'].'active.png';
		return \Yii::$app->params['appImageUrl'].'inactive.png';
	}

	public function get_category_name($category_id)
	{
		$cat_id=explode(',',$category_id);
		if(count($cat_id)>1){
		$cat_id=explode(',',$category_id);
		$cat='';
		foreach($cat_id as $c)
		{
			$cate=Yii::$app->DB->createcommand("Select category_name from whitebook_category where category_id=$c")->queryAll();
			$cat.=$cate[0]['category_name'].',';
		}
		return rtrim($cat,',');
	}
	else
	{
		$cate=Yii::$app->DB->createcommand("Select category_name from whitebook_category where category_id=$category_id")->queryAll();
		return $cate[0]['category_name'];
	}
	}
}
