<?php

namespace common\models;

use Yii;
use common\models\Category;

/**
 * This is the model class for table "whitebook_category_path".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $path_id
 * @property integer $level
 */
class CategoryPath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_category_path';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'path_id', 'level'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'path_id' => 'Path ID',
            'level' => 'Level',
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
     * @inheritdoc
     * @return BookingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\CategoryPathQuery(get_called_class());
    }
}
