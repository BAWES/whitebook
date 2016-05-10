<?php

namespace backend\models;
use backend\models\Vendoritem;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "{{%vendor_item_capacity_exception}}".
 *
 * @property string $exception_id
 * @property string $item_id
 * @property string $exception_date
 * @property integer $exception_capacity
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Vendoritemcapacityexception extends \yii\db\ActiveRecord
{


}
