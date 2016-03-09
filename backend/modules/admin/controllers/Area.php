<?php

namespace backend\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%area}}".
 *
 * @property string $area_id
 * @property int $area_name
 * @property int $created_by
 * @property int $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%area}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_name'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'area_id' => 'Area ID',
            'area_name' => 'Area Name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
    public static function loadarea()
    {
        $area = self::find()->all();

        return $area = ArrayHelper::map($area, 'area_id', 'area_name');
    }
}
