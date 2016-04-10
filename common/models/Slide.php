<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%slide}}".
 *
 * @property integer $slide_id
 * @property string $slide_title
 * @property string $slide_image
 * @property string $slide_video_url
 * @property string $slide_url
 * @property string $slide_status
 * @property integer $sort
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Slide extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slide_title','slide_type'], 'required'],
            [['slide_status', 'trash'], 'string'],
            [['slide_url'], 'url'],
            [['sort', 'created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            ['slide_video_url',  'file', 'maxFiles'=>0,'extensions' => 'mp4,avi','skipOnEmpty' => false,'maxSize' => 1024 * 1024 * 20,  'when' => function ($model) {
			        return $model->slide_type == 'video';
			    }, 'whenClient' => "function (attribute, value) {
			        return $('#slide-slide_type').val() == 'video';
			    }"
			],
	        ['slide_image',  'image', 'maxFiles'=>0,'extensions' => 'png,jpg, jpeg','skipOnEmpty' => false,'maxSize' => 1024 * 1024 * 20,
		         'when' => function ($model) {
		        	return $model->slide_type == 'image';
			    }, 'whenClient' => "function (attribute, value) {
			        return $('#slide-slide_type').val() == 'image';
			    }"
			],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'slide_id' => 'Slide ID',
            'slide_title' => 'Slide Title',
            'slide_image' => 'Slide Image',
            'slide_video_url' => 'Slide Video Url',
            'slide_url' => 'Slide Url',
            'slide_status' => 'Slide Status',
            'slide_type'=>'slide type',
            'sort' => 'Sort',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
}
