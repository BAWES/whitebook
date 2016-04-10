<?php

namespace admin\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "Slide".
 * It extends from \common\models\Slide but with custom functionality for admin portal
 *
 */
class Slide extends \common\models\Slide {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            /**
             * Uploading Files is required on Create Scenario
             */
            ['slide_video_url',  'file', 'extensions' => 'mp4,avi','skipOnEmpty' => false,'maxSize' => 1024 * 1024 * 20,
                'when' => function ($model) {
			        return $model->slide_type == 'video';
			    }, 'whenClient' => "function (attribute, value) {
			        return $('#slide-slide_type').val() == 'video';
			    }", 'on' => ['create', 'update']
			],
	        ['slide_image',  'image', 'extensions' => 'png,jpg,jpeg', 'minWidth' => 1600,
                             'skipOnEmpty' => false,'maxSize' => 1024 * 1024 * 20,
		        'when' => function ($model) {
		        	return $model->slide_type == 'image';
			    }, 'whenClient' => "function (attribute, value) {
			        return $('#slide-slide_type').val() == 'image';
			    }", 'on' => ['create', 'update']
			],
        ]);
    }

    /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();

        //$scenarios['uploadImage'] = ['slide_image'];

        return $scenarios;
    }


    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                //if new record
            }

            //All validations pass, upload the files to S3
            if ($this->slide_type == 'video') {
                $this->uploadVideo();
            }else{
                $this->uploadImage();
            }

            return true;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            //Delete the uploaded files from S3
            $this->deleteFiles();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes uploaded files related to this model from S3
     */
    public function deleteFiles(){
        Yii::$app->resourceManager->delete(self::UPLOADFOLDER. $this->slide_image);
        Yii::$app->resourceManager->delete(self::UPLOADFOLDER . $this->slide_video_url);
    }

    /**
     * Uploads the new image if $this->slide_image is an instance of UploadedFile
     */
    public function uploadImage() {
        if($this->slide_image instanceof yii\web\UploadedFile){
            $filename = Yii::$app->security->generateRandomString() . "." . $this->slide_image->extension;

            //Resize file using imagine
            $resize = true;

            if($resize){
                $newTmpName = $this->slide_image->tempName . "." . $this->slide_image->extension;

                $imagine = new \Imagine\Gd\Imagine();
                $image = $imagine->open($this->slide_image->tempName);
                $image->resize($image->getSize()->widen(1600));
                $image->save($newTmpName);

                //Overwrite old filename for S3 uploading
                $this->slide_image->tempName = $newTmpName;
            }

            //Save to S3
            $awsResult = Yii::$app->resourceManager->save($this->slide_image, self::UPLOADFOLDER . $filename);
            if($awsResult){
                $this->slide_image = $filename;
            }
        }
    }

    /**
     * Uploads the new video if $this->slide_video_url is an instance of UploadedFile
     */
    public function uploadVideo() {
        if($this->slide_video_url instanceof yii\web\UploadedFile){
            $filename = Yii::$app->security->generateRandomString() . "." . $this->slide_video_url->extension;

            //Save to S3
            $awsResult = Yii::$app->resourceManager->save($this->slide_video_url, self::UPLOADFOLDER . $filename);
            if($awsResult){
                $this->slide_video_url = $filename;
            }
        }
    }

}
