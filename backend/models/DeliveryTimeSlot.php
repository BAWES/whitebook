<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "{{%vendor_delivery_timeslot}}".
*
* @property string $timeslot_id
* @property string $vendor_id
* @property string $timeslot_day
* @property string $timeslot_start_time
* @property string $timeslot_end_time
* @property integer $timeslot_maximum_orders
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*/
class DeliveryTimeSlot extends \common\models\DeliveryTimeSlot
{

	/**
	 * @inheritdoc
	 */
	public $start_hr;
	public $end_hr;
	public $start_min;
	public $end_min;
	public $start_med;
	public $end_med;
	public static function tableName()
	{
		return '{{%vendor_delivery_timeslot}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['timeslot_day','start_hr', 'end_hr','start_min','start_med','end_min','end_med', 'timeslot_maximum_orders'],'required'],
			[['timeslot_maximum_orders', 'created_by', 'modified_by'], 'integer'],
			[['timeslot_start_time', 'timeslot_end_time', 'created_datetime', 'modified_datetime'], 'safe'],
			[['trash'], 'string'],
			[['timeslot_day'], 'string', 'max' => 64]
		];
	}


    public static function deliverytimeslot($day)
    {
        return $time_slot = DeliveryTimeSlot::find()
        	->where([
        		'vendor_id' => Yii::$app->user->getId(), 
        		'timeslot_day' => $day
        	])
        	->asArray()
        	->all();
    }

    public static function vendor_deliverytimeslot($id,$day)
    {
        return $time_slot = DeliveryTimeSlot::find()
        	->where(['vendor_id' => $id, 'timeslot_day' => $day])
        	->asArray()
        	->all();
    }

	public function behaviors()
	{
		return [
			[
				'class' => BlameableBehavior::className(),
				'createdByAttribute' => 'created_by',
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
			'timeslot_id' => 'Timeslot ID',
			'vendor_id' => 'Vendor ID',
			'timeslot_day' => 'Time slot Day',
			'timeslot_start_time' => 'Time slot Start Time',
			'timeslot_end_time' => 'Time slot End Time',
			'timeslot_maximum_orders' => 'Time slot Maximum Orders',
			'created_by' => 'Created By',
			'modified_by' => 'Modified By',
			'created_datetime' => 'Created Datetime',
			'modified_datetime' => 'Modified Datetime',
			'start_hr'=>'Start hour',
			'start_min'=>'Start minutes',
			'start_med'=>'Start meridien',
			'end_hr'=>'End hour',
			'end_min'=>'End minutes',
			'end_med'=>'End meridien',
			'trash' => 'Trash',
		];
	}

}
