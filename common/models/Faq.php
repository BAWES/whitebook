<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_faq".
 *
 * @property integer $faq_id
 * @property string $question
 * @property string $answer
 * @property string $faq_status
 * @property integer $sort
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer', 'sort', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            [['question', 'answer', 'faq_status', 'trash'], 'string'],
            [['sort', 'created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'faq_id' => 'Faq ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'faq_status' => 'Faq Status',
            'sort' => 'Sort',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

}
