<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "whitebook_suborder".
 *
 * @property string $suborder_id
 * @property string $order_id
 * @property string $vendor_id
 * @property string $status_id
 * @property string $suborder_delivery_charge
 * @property string $suborder_total_without_delivery
 * @property string $suborder_total_with_delivery
 * @property string $suborder_commission_percentage
 * @property string $suborder_commission_total
 * @property string $suborder_vendor_total
 * @property string $suborder_datetime
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Suborder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_suborder';
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
            [['order_id', 'vendor_id', 'status_id', 'suborder_delivery_charge', 'suborder_total_without_delivery', 'suborder_total_with_delivery', 'suborder_commission_percentage', 'suborder_commission_total', 'suborder_vendor_total', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            [['order_id', 'vendor_id', 'status_id', 'created_by', 'modified_by'], 'integer'],
            [['suborder_delivery_charge', 'suborder_total_without_delivery', 'suborder_total_with_delivery', 'suborder_commission_percentage', 'suborder_commission_total', 'suborder_vendor_total'], 'number'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suborder_id' => 'Suborder ID',
            'order_id' => 'Order ID',
            'vendor_id' => 'Vendor ID',
            'status_id' => 'Status ID',
            'profit' => 'Profit (KWD)',
            'suborder_delivery_charge' => 'Suborder Delivery Charge',
            'suborder_total_without_delivery' => 'Suborder Total Without Delivery',
            'suborder_total_with_delivery' => 'Suborder Total With Delivery',
            'suborder_commission_percentage' => 'Suborder Commission Percentage',
            'suborder_commission_total' => 'Suborder Commission Total',
            'suborder_vendor_total' => 'Suborder Vendor Total',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    public function getOrder() {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    public function getStatus() {
        return $this->hasOne(OrderStatus::className(), ['order_status_id' => 'status_id']);
    }

    public function getStatusName() {
        return $this->status->name;
    }

    public function getItemPurchased() {
        return $this->hasOne(SuborderItemPurchase::className(),['suborder_id'=>'suborder_id']);
    }

    public static function getReportQuery($data = array())
    {    
        $query = Suborder::find()
          ->select('
              MIN(created_datetime) AS date_start, 
              MAX(created_datetime) AS date_end, 
              COUNT(*) AS `suborder_count`,
              SUM(suborder_commission_total) AS `commission`,
          ')
          ->where(['trash' => 'Default']);

        if (!empty($data['vendor_id'])) {
          $query->andWhere('vendor_id = ' . $data['vendor_id']);
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

    public function getProfit() {
        $profit = $this->suborder_vendor_total - $this->suborder_commission_total;
        return $profit;
    }
}
