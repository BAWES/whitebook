<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "whitebook_image".
 *
 * @property string $image_id
 * @property string $image_user_id
 * @property string $image_user_type
 * @property string $image_path
 * @property string $image_file_size
 * @property string $image_width
 * @property string $image_height
 * @property string $image_datetime
 * @property string $image_ip_address
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property VendorItemImage[] $vendorItemImages
 * @property VendorItemQuestionAnswerOption[] $vendorItemQuestionAnswerOptions
 * @property VendorItemQuestionGuide[] $vendorItemQuestionGuides
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_image';
    }

        public function behaviors()
    {
          return [
              [
                      'class' => BlameableBehavior::className(),
                      'createdByAttribute' => 'created_by',
                      'updatedByAttribute' => 'modified_by',
                  ],
                  'timestamp' => 
                  [
                      'class' => 'yii\behaviors\TimestampBehavior',
                      'attributes' => [
                       ActiveRecord::EVENT_BEFORE_INSERT => ['created_datetime'],
                       ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_datetime'],
                         
                      ],
                     'value' => new Expression('NOW()'),
                  ],
          ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['image_user_id', 'image_file_size', 'image_width', 'image_height', 'image_datetime', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            [['image_path'], 'file','extensions' => ['png', 'jpg', 'gif','jpeg']], 
            [['image_user_id', 'created_by', 'modified_by'], 'integer'],
            [['image_user_type', 'trash'], 'string'],
            [['image_file_size', 'image_width', 'image_height'], 'number'],
            [['image_datetime', 'image_path','created_datetime', 'modified_datetime','vendorimage_sort_order'], 'safe'],
            [['image_ip_address'], 'string', 'max' => 128]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['Vendoritemupdate'] = ['image_path'];
        return $scenarios;      
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'image_user_id' => 'Image User ID',
            'image_user_type' => 'Image User Type',
            'image_path' => 'Image Path',
            'image_file_size' => 'Image File Size',
            'image_width' => 'Image Width',
            'image_height' => 'Image Height',
            'image_datetime' => 'Image Datetime',
            'image_ip_address' => 'Image Ip Address',
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
    public function getVendorItemImages()
    {
        return $this->hasMany(VendorItemImage::className(), ['image_id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorItemQuestionAnswerOptions()
    {
        return $this->hasMany(VendorItemQuestionAnswerOption::className(), ['answer_background_image_id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorItemQuestionGuides()
    {
        return $this->hasMany(VendorItemQuestionGuide::className(), ['guide_image_id' => 'image_id']);
    }
    
	public static function deleteImage() {
    $image = Yii::$app->basePath . '/uploads/vendor_images' . $this->image_file;
    if (unlink($image)) {
        $this->image_file = null;
        $this->save();
        return true;
    }
    return false;
	}

    
    // Only for Service and Rental
    public static function loadserviceguideimageids($image_id)
    {
        $model = Image::find()->where(['image_id'=>$image_id,'module_type'=>'guides'])->all();
        return $model;
    }
}
