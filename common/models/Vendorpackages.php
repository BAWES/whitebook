<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "{{%vendor_packages}}".
*
* @property integer $id
* @property integer $vendor_id
* @property integer $package_id
* @property double $package_price
* @property string $created_datetime
* @property string $modified_datetime
* @property integer $created_by
* @property integer $modified_by
*/
class Vendorpackages extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%vendor_packages}}';
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
    public function rules()
    {
        return [
            [['vendor_id', 'package_id', 'package_price'], 'required'],
            [['vendor_id', 'package_id', 'created_datetime', 'modified_datetime', 'created_by', 'modified_by'], 'integer'],
            [['package_price'], 'number'],
            [['created_datetime', 'modified_datetime'], 'safe']
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vendor_id' => 'Vendor ID',
            'package_id' => 'Package Name',
            'package_price' => 'Price',
            'package_end_date' => 'End date',
            'package_start_date' => 'Start date',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['package_id' => 'package_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public static function getReportQuery($data = array())
    {    
        $query = Vendorpackages::find()
          ->select('
              MIN(created_datetime) AS date_start, 
              MAX(created_datetime) AS date_end, 
              COUNT(*) AS `package_count`,
              SUM(package_price) AS `package_price_sum`,
          ')
          ->where(['trash' => 'Default']);

        if (!empty($data['package_id'])) {
          $query->andWhere('package_id = ' . $data['package_id']);
        } 

        if (!empty($data['date_start'])) {
          $query->andWhere("DATE(created_datetime) >= '" . $data['date_start'] . "'");
        }

        if (!empty($data['date_end'])) {
          $query->andWhere("DATE(created_datetime) <= '" . $data['date_end'] . "'");
        }

        if (!empty($data['group_by'])) {
          $group = $data['group_by'];
        } else {
          $group = 'day';
        }

        switch($group) {
          case 'day';
            $query->groupBy("YEAR(created_datetime), MONTH(created_datetime), DAY(created_datetime)");
            break;
          default:
          case 'week':
            $query->groupBy("YEAR(created_datetime), WEEK(created_datetime)");
            break;
          case 'month':
            $query->groupBy("YEAR(created_datetime), MONTH(created_datetime)");
            break;
          case 'year':
            $query->groupBy("YEAR(created_datetime)");
            break;
        }

        $query->orderBy("created_datetime DESC");

        return $query;
    }
}
