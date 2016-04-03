<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%advert_category}}".
 *
 * @property string $advert_id
 * @property string $category_id
 * @property string $top_ad
 * @property string $bottom_ad
 * @property string $advert_code
 * @property string $status
 * @property integer $sort
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property integer $created_by
 * @property integer $modified_by
 */
class categoryads extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
            [['category_id' ], 'required'],
            [['top_ad','bottom_ad'],'checkOne','skipOnEmpty' => false, 'skipOnError' => false],          
            [['status'], 'string'],
            [['sort', 'created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime','top_ad','bottom_ad'], 'safe'],           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advert_id' => 'Advert ID',
            'category_id' => 'Category name',
            'top_ad' => 'Top ad code',
            'bottom_ad' => 'Bottom ad code',
            'advert_code' => 'Advert Code',
            'status' => 'Status',
            'sort' => 'Sort',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
        ];
    }

    public function checkOne($attribute, $params)
    {        
       if($this->top_ad =='' && $this->bottom_ad =='') {
             $this->addError($attribute, 'Enter advertisement code atleast one field.');                   
        }
    }

    public static function statusImageurl($status)
    {           
        if($status == 'Unblock')        
        return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
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
