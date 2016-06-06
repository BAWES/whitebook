<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%slide}}".
 *
 * @property integer $slide_id
 * @property string $slide_title
 * @property string $slide_type
 * @property string $slide_image
 * @property string $slide_video_url
 * @property string $slide_url
 * @property string $slide_status
 * @property integer $sort
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Slide extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    const UPLOADFOLDER = "slider_uploads/";

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
            [['sort'], 'integer'],
        ];
    }

public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
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
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    /**
     * @return string path to the image
     */
    public function getImage(){
        if($this->slide_image){
            //Return link to photo uploaded in S3 bucket
            return Url::to("@".self::UPLOADFOLDER.$this->slide_image);
        }else return false;
    }

    /**
     * @return string path to the video
     */
    public function getVideo(){
        if($this->slide_video_url){
            //Return link to photo uploaded in S3 bucket
            return Url::to("@".self::UPLOADFOLDER.$this->slide_video_url);
        }else return false;
    }
}
