<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorItemCapacityException]].
 *
 * @see Booking
 */
class VendorItemCapacityExceptionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorItemCapacityException[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemCapacityException|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($ID){
        return $this->andWhere(['item_id' => $ID]);
    }

    public function exceptionDate($date){
        return $this->andWhere(['exception_date' => $date]);
    }

    public function defaultException(){
        return $this->andWhere(['trash'=>'Default']);
    }
}