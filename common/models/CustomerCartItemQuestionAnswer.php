<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_cart_item_question_answer}}".
 *
 * @property integer $cart_item_question_answer_id
 * @property integer $cart_id
 * @property integer $question_id
 * @property integer $item_id
 * @property string $answer
 * @property string $created_datetime
 * @property string $modified_datetime
 */
class CustomerCartItemQuestionAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_cart_item_question_answer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cart_id', 'question_id', 'item_id'], 'required'],
            [['cart_id', 'question_id', 'item_id'], 'integer'],
            [['answer'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cart_item_question_answer_id' => Yii::t('app', 'Cart Item Question Answer ID'),
            'cart_id' => Yii::t('app', 'Cart ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'answer' => Yii::t('app', 'Answer'),
            'created_datetime' => Yii::t('app', 'Created Datetime'),
            'modified_datetime' => Yii::t('app', 'Modified Datetime'),
        ];
    }

    /**
     * @inheritdoc
     * @return query\CustomerCartItemQuestionAnswerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\CustomerCartItemQuestionAnswerQuery(get_called_class());
    }

    public function getQuestion() {
        return $this->hasOne(VendorItemQuestion::className(),['item_question_id'=>'question_id']);
    }

    public function getCartQuestionAnswer($cart_id) {
        return CustomerCartItemQuestionAnswer::find()->where(['cart_id'=>$cart_id])->all();
    }
}
