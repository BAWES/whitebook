<?php

namespace backend\models;

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
            [['question', 'answer'], 'required'],
            [['question', 'answer', 'faq_status', 'trash'], 'string'],
            //[['created_by', 'modified_by','sort'], 'integer'],
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
            'faq_status' => 'Faq Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'sort' => 'Sort Order',
        ];
    }
    
    public static function statusImageurl($img_status)
	{
		if($img_status == 'Active')		
		return \Yii::$app->params['appImageUrl'].'active.png';
		return \Yii::$app->params['appImageUrl'].'inactive.png';
	}
	public static function faq_details()
	{
        $faq= Faq::find()
			->select(['question','answer'])
            ->where(['faq_status'=>'active'])           
            ->andwhere(['trash'=>'default'])
            ->all();   
            return $faq;
     }
	
    
}
