<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "whitebook_faq_group".
 *
 * @property integer $faq_group_id
 * @property string $group_name
 * @property string $group_name_ar
 */
class FaqGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_faq_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name', 'group_name_ar'], 'required'],
            [['group_name', 'group_name_ar'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'faq_group_id' => 'Faq Group ID',
            'group_name' => 'Group Name',
            'group_name_ar' => 'Group Name - Arabic',
            'sort_order' => 'Sort order',
        ];
    }
}
