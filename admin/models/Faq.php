<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "whitebook_faq".
 *
 * @property integer $faq_id
 * @property string $question
 * @property string $answer
 * @property string $faq_status
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Faq extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    
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
            [['question', 'answer', 'question_ar', 'answer_ar'], 'required'],
            [['question', 'answer', 'question_ar', 'answer_ar', 'faq_status', 'trash'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe']
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
            'question_ar' => 'Question - Arabic',
            'answer_ar' => 'Answer - Arabic',
            'faq_status' => 'Faq Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'sort' => 'Sort Order',
        ];
    }
    
    public function statusImageurl($img_status)
	{
		if($img_status == 'Active')		
		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
	}

    // Status Image title
    public function statusTitle($status)
    {           
        if($status == 'Active')     
            return 'Activate';
        
        return 'Deactivate';
    }

	public static function faq_details()
	{
        return $faq = Faq::find()
			->select(['question','answer'])
            ->where(['faq_status'=>'active'])
            ->andwhere(['trash'=>'default'])
            ->all();
     }
}
