<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property int $created_at
 * @property int $updated_at
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 */
class Authitem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type'], 'integer'],
            [['description', 'data'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['name', 'rule_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    public static function Authitem()
    {
        $authitem = self::find()->all();
        $authitem = ArrayHelper::map($authitem, 'name', 'name');

        return $authitem;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    public function AuthitemCheck($type, $controllerid)
    {
        $item = self::find()
            ->select(['name'])
            ->where(['id' => $type])
            ->one();
        $itemname = $item['name'];
        $id = Admin::getAdmin('id');
        $final1 = Authassignment::find()
            ->select(['item_name'])
            ->where(['item_name' => $itemname])
            ->andwhere(['user_id' => $id])
            ->andwhere(['controller_id' => $controllerid])
            ->one();

        return $final1['item_name'];
    }
}
