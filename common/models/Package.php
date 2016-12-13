<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "whitebook_package".
 *
 * @property integer $package_id
 * @property string $package_name
 * @property string $package_background_image
 * @property string $package_description
 * @property string $package_avg_price
 * @property string $package_number_of_guests
 */
class Package extends \yii\db\ActiveRecord
{
    const UPLOAD_FOLDER = "package/";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_package';
    }

    /**
     * @var UploadedFile
     */
    public $imageFile;
    

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'slugAttribute' => 'package_slug',
                'attribute' => 'package_name',
                'immutable' => true,
                'ensureUnique'=>true,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['package_name', 'status'], 'required'],
            [['package_description', 'package_description_ar'], 'string'],
            [['package_name', 'package_avg_price', 'package_number_of_guests'], 'string', 'max' => 100],
            [['package_background_image', 'package_slug'], 'string', 'max' => 250],
            [['imageFile'], 'image', 'skipOnEmpty' => true, 'minWidth' => 200, 'maxWidth' => 1250, 'minHeight' => 200, 'maxHeight' => 1250, 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'package_id' => Yii::t('app', 'Package ID'),
            'package_name' => Yii::t('app', 'Package Name'),
            'package_name_ar' => Yii::t('app', 'Package Name - Arabic'),
            'package_background_image' => Yii::t('app', 'Package Background Image'),
            'package_description' => Yii::t('app', 'Package Description'),
            'package_description_ar' => Yii::t('app', 'Package Description - Arabic'),
            'package_avg_price' => Yii::t('app', 'Package Avg Price'),
            'package_number_of_guests' => Yii::t('app', 'Package Number Of Guests'),
            'package_slug' => Yii::t('app', 'package_slug'),
            'status' => Yii::t('app', 'Status')
        ];
    }
}
