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
class VendorItemCapacityException extends \common\models\VendorItemCapacityException
{

    public function rules()
    {
        return [
            [['item_id', 'exception_date', 'exception_capacity'], 'required'],
            [['exception_capacity', 'created_by', 'modified_by'], 'integer'],
            [['created_by', 'modified_by', 'exception_date', 'created_datetime', 'modified_datetime'], 'safe'],
            ['item_id', 'alreadyExist'],
            [['trash'], 'string'],
        ];
    }


    public function alreadyExist($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->isNewRecord) {
                $data = VendorItemCapacityException::findOne(['item_id' => $this->item_id, 'exception_date' => date('Y-m-d', strtotime($this->exception_date))]);
                if ($data) {
                    $this->addError($attribute, 'Item exception already exist for provided date');
                }
            }
        }
    }

    public function behaviors()
    {
        return parent::behaviors();
    }

}
