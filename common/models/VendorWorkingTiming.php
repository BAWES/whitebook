<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_working_timing}}".
 *
 * @property integer $working_id
 * @property integer $vendor_id
 * @property string $working_day
 * @property string $working_start_time
 * @property string $working_end_time
 * @property string $trash
 */
class VendorWorkingTiming extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_working_timing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['working_day', 'working_start_time', 'working_end_time'], 'required'],
            [['vendor_id'], 'integer'],
            [['working_start_time'], 'validateStartTime'],
            [['working_end_time'], 'validateEndTime'],
            [['working_day', 'trash'], 'string'],
            [['working_start_time', 'working_end_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'working_id' => Yii::t('app', 'Working ID'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'working_day' => Yii::t('app', 'Working Day'),
            'working_start_time' => Yii::t('app', 'Working Start Time'),
            'working_end_time' => Yii::t('app', 'Working End Time'),
            'trash' => Yii::t('app', 'Trash'),
        ];
    }

    public function validateStartTime() 
    {
        $start_time = strtotime($this->working_start_time);
        $end_time = strtotime($this->working_end_time);

        $timeslots = VendorWorkingTiming::find()
            ->vendor($this->vendor_id)
            ->workingDay($this->working_day)
            ->defaultTiming()->all();

        foreach ($timeslots as $key => $value) {

            if($value->working_id == $this->working_id)
                        continue;

            if($start_time > strtotime($value->working_start_time) && 
                $start_time < strtotime($value->working_end_time)) {
                return $this->addError('working_start_time', 'You already have working time slot from '.date('h:i A', strtotime($value->working_start_time)).' - '.date('h:i A', strtotime($value->working_end_time)).'. on '.$value->working_day);
            }
        }
    }

    public function validateEndTime() 
    {
        $start_time = strtotime($this->working_start_time);
        $end_time = strtotime($this->working_end_time);

        $timeslots = VendorWorkingTiming::find()
            ->vendor($this->vendor_id)
            ->workingDay($this->working_day)
            ->defaultTiming()->all();
        
        foreach ($timeslots as $key => $value) {
            
            if($value->working_id == $this->working_id)
                        continue;

            if($end_time > strtotime($value->working_start_time) && 
                $end_time < strtotime($value->working_end_time)) {
                return $this->addError('working_end_time', 'You already have working time slot from '.date('h:i A', strtotime($value->working_start_time)).' - '.date('h:i A', strtotime($value->working_end_time)).'. on '.$value->working_day);
            }
        }
    }

    /**
     * @inheritdoc
     * @return query\VendorWorkingTimingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\VendorWorkingTimingQuery(get_called_class());
    }
}