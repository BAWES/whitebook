<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_item_question}}".
 *
 * @property string $question_id
 * @property string $item_id
 * @property string $answer_id
 * @property string $question_text
 * @property string $question_answer_type
 * @property integer $question_max_characters
 * @property integer $question_sort_order
 * @property string $question_archived
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Vendoritemquestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_item_question}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_text','question_answer_type',], 'required'],
            [['question_max_characters','question_sort_order', 'created_by', 'modified_by'], 'integer'],
            [['question_answer_type','guide_caption', 'trash'], 'string'],
            [['answer_id','guide_caption','item_id','question_answer_type','question_max_characters', 'question_sort_order','created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'safe'],
            [['question_text'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => 'Question',
            'item_id' => 'Item ',
            'question_text' => 'Question Text',
            'question_answer_type' => 'Question Type',
            'question_max_characters' => 'Answer Max Characters',
            'question_sort_order' => 'Question Sort Order',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',

        ];
    }
}
