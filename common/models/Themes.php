<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
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
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";

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
                  'attribute' => 'category_name',
              ],
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
}
