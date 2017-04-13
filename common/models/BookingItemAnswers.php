<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%booking_item_answers}}".
 *
 * @property integer $answer_id
 * @property integer $booking_id
 * @property integer $item_id
 * @property string $question
 * @property string $answer
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class BookingItemAnswers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%booking_item_answers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['booking_id', 'item_id', 'question'], 'required'],
            [['booking_id', 'item_id'], 'integer'],
            [['answer', 'trash'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'answer_id' => Yii::t('app', 'Answer ID'),
            'booking_id' => Yii::t('app', 'Booking ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'question' => Yii::t('app', 'Question'),
            'answer' => Yii::t('app', 'Answer'),
            'created_datetime' => Yii::t('app', 'Created Datetime'),
            'modified_datetime' => Yii::t('app', 'Modified Datetime'),
            'trash' => Yii::t('app', 'Trash'),
        ];
    }

    /**
     * @inheritdoc
     * @return query\BookingItemAnswersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\BookingItemAnswersQuery(get_called_class());
    }

    public static function saveItemAnswers($cart_id,$booking_id) {

        $CartData = CustomerCartItemQuestionAnswer::findAll(['cart_id'=>$cart_id]);
        if ($CartData) {
            foreach ($CartData as $answer) {
                $modal = new BookingItemAnswers();
                $modal->booking_id = $booking_id;
                $modal->item_id = $answer->item_id;
                $modal->question = (isset($answer->question->question)) ? $answer->question->question : '';
                $modal->answer = $answer->answer;
                $modal->created_datetime = date('Y-m-d H-i-s');
                $modal->modified_datetime = date('Y-m-d H-i-s');
                $modal->trash = 'Default';
                $modal->save(false);
            }
            CustomerCartItemQuestionAnswer::deleteAll(['cart_id'=>$cart_id]);
        }
    }
}
