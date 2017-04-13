<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_draft_item_question}}".
 *
 * @property integer $item_question_id
 * @property integer $item_id
 * @property string $question
 * @property integer $required
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class VendorDraftItemQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_draft_item_question}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id'], 'required'],
            [['item_id', 'required'], 'integer'],
            [['question', 'trash'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_question_id' => Yii::t('app', 'Item Question ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'question' => Yii::t('app', 'Question'),
            'required' => Yii::t('app', 'Required'),
            'created_datetime' => Yii::t('app', 'Created Datetime'),
            'modified_datetime' => Yii::t('app', 'Modified Datetime'),
            'trash' => Yii::t('app', 'Trash'),
        ];
    }

    /**
     * @inheritdoc
     * @return VendorDraftItemQuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VendorDraftItemQuestionQuery(get_called_class());
    }

    public static function create_from_item($id) {
        $data = VendorItemQuestion::find()->item($id)->all();

        if ($data) {
            foreach ($data as $questions) {
                $model = new VendorDraftItemQuestion();
                $model->item_id = $questions->item_id;
                $model->question = $questions->question;
                $model->required = $questions->required;
                $model->trash = $questions->trash;
                $model->save();
            }
        }
    }

    public static function approved($id) {
        $data = VendorDraftItemQuestion::find()->item($id)->all();

        if ($data) {
            VendorItemQuestion::deleteAll(['item_id'=>$id]);
            foreach ($data as $questions) {
                $model = new VendorItemQuestion();
                $model->item_id = $questions->item_id;
                $model->question = $questions->question;
                $model->required = $questions->required;
                $model->trash = $questions->trash;
                $model->save();
            }
            VendorDraftItemQuestion::deleteAll(['item_id'=>$id]);
        }
    }
}
