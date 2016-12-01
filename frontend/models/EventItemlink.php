<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\EventCategoryCompleted;

/**
* This is the model class for table "{{%wishlist}}".
*
* @property integer $invitees_id
* @property integer $event_id
* @property string $name
* @property string $email
* @property string $phone_number
* @property string $created_datetime
* @property string $modified_datetime
* @property integer $created_by
* @property integer $modified_by
*/
class EventItemlink extends \yii\db\ActiveRecord
{
    const EVENT_ITEM_LINK_EXIST = -2;
    const EVENT_ITEM_CREATED = 2;
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%event_item_link}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['event_id', 'item_id'], 'required'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'modified_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'link_id' => 'Link ID',
            'link_datetime' => 'Link Date time',
        ];
    }

    public static function is_cat_complete($event_id, $category_id) 
    {
        $count = EventCategoryCompleted::find()
            ->where([
                'category_id' => $category_id,
                'event_id' => $event_id
            ])
            ->count();
        
        if($count) {
            return true;
        }

        return false;
    }

    public static function markComplete($event_id, $category_id) 
    {
        $model = new EventCategoryCompleted();
        $model->event_id = $event_id;
        $model->category_id = $category_id;
        $model->save();
    }

    public static function markIncomplete($event_id, $category_id) 
    {
        EventCategoryCompleted::deleteAll([
            'event_id' => $event_id,
            'category_id' => $category_id
        ]);
    }
}
