<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_item_question_answer_option}}".
 *
 * @property string $answer_id
 * @property string $question_id
 * @property string $answer_background_image_id
 * @property string $answer_text
 * @property string $answer_background_color
 * @property string $answer_price_added
 * @property string $answer_archived
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Vendoritemquestionansweroption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_item_question_answer_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id'], 'required'],
            [['question_id', 'created_by', 'modified_by'], 'integer'],
            [['answer_price_added'], 'number'],
            [['answer_archived', 'trash'], 'string'],
            [['created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'safe'],
            [['answer_text'], 'string', 'max' => 128],
            [['answer_background_color'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'answer_id' => 'Answer',
            'question_id' => 'Question',
            'answer_background_image_id' => 'Answer Background Image',
            'answer_text' => 'Answer Text',
            'answer_background_color' => 'Answer Background Color',
            'answer_price_added' => 'Answer Price Added',
            'answer_archived' => 'Answer Archived',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
}
