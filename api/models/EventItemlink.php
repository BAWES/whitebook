<?php

namespace api\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
class EventItemlink extends \common\models\EventItemlink
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
