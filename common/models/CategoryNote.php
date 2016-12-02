<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_category_note".
 *
 * @property integer $category_note_id
 * @property string $customer_id
 * @property string $category_id
 *
 * @property Category $category
 * @property Customer $customer
 */
class CategoryNote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_category_note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'category_id', 'event_id'], 'integer'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'event_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_note_id' => Yii::t('app', 'Category Note ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    public static function getNote($category_id, $event_id) 
    {
        $query = CategoryNote::find()
            ->where([
                'customer_id' => Yii::$app->user->getId(),
                'category_id' => $category_id,
                'event_id' => $event_id
            ])
            ->one();

        if($query)
        {
            return $query->note;
        } 
    }   
}
