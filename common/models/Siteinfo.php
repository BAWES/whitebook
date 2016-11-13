<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Siteinfo extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%siteinfo}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['name', 'value'], 'required']
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value'
        ];
    }

    public function info($name)
    {
        $result = Siteinfo::find()
            ->where(['name' => $name])
            ->one();

        if($result) {
            return $result->value;
        }
    }
}
