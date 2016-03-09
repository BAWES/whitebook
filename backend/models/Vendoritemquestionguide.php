<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_item_question_guide}}".
 *
 * @property string $guide_id
 * @property string $question_id
 * @property string $guide_image_id
 * @property string $guide_caption
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Vendoritemquestionguide extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_item_question_guide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id'], 'required'],
            [['question_id', 'created_by', 'modified_by'], 'integer'],
            [['guide_caption', 'trash'], 'string'],
            [['created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'guide_id' => 'Guide ',
            'question_id' => 'Question',
            'guide_image_id' => 'Guide Image',
            'guide_caption' => 'Guide Caption',
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
    public function getQuestion()
    {
        return $this->hasOne(VendorItemQuestion::className(), ['question_id' => 'question_id']);
    }
    
}
