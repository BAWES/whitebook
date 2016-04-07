<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

 /**
  * This is the model class for table "{{%banner}}".
  *
  * @property integer $banner_id
  * @property string $banner_title
  * @property string $banner_image
  * @property string $banner_video_url
  * @property string $banner_url
  * @property string $banner_status
  * @property integer $sort
  * @property integer $created_by
  * @property integer $modified_by
  * @property string $created_datetime
  * @property string $modified_datetime
  * @property string $trash
  */

class Banner extends \yii\db\ActiveRecord
{
	public $banner_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['banner_title','banner_type'], 'required'],
            [['banner_url'], 'url'],
            [['sort'], 'integer'],
            [['banner_title'], 'string', 'max' => 100],
            //[['banner_status'], 'string', 'max' => 5],
            [['banner_type','banner_video_url'], 'safe'],
		          ['banner_image', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => false,'minWidth' => 1300, 'maxWidth' => 1300,'minHeight' => 550, 'maxHeight' =>550,'on' => 'register'],

        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'banner_id' => 'Banner ID',
            'banner_title' => 'Banner Title',
            'banner_url' => 'Banner URL',
            'banner_image' => 'Banner Image',
            'banner_video_url' => 'Banner Video',
            'banner_status' => 'Banner Status',
            'banner_type'=>'Banner Type',
            'default' => 'Default',
            'sort' => 'Sort',
        ];
    }

    public static function statusImageurl($status)
	{
		if($status == 'Active')
		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
	}


	public static function loadbanner()
	{
			$Banner= Banner::find()
			->where(['!=', 'banner_status', 'Deactive'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->all();
			$Banner=ArrayHelper::map($Banner,'banner_id','banner_title');
			return $Banner;
	}

	public static function getStatus($status)
    {
		$query = new Query;
		$query->select('name')
			  ->from('whitebook_status1')
			  ->where(['status' => $status]);
		$command = $query->createCommand();
		$data = $command->queryOne();
		return $data['name'];
    }
}
