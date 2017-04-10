<?php

namespace common\models\query;

use Yii;
/**
 * This is the ActiveQuery class for [[Location]].
 *
 * @see Booking
 */
class LocationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Location[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Location|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function city($id) {
        return $this->where(['city_id' => $id]);
    }

    public function active() {
        return $this->where(['status'=>'Active']);
    }

    public function defaultLocations() {
        return $this->where(['trash' => 'Default']);
    }
}