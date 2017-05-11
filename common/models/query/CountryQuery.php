<?php

namespace common\models\query;

use Yii;
/**
 * This is the ActiveQuery class for [[Country]].
 *
 * @see Booking
 */
class CountryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Country[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Country|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function defaultCountry() {
        return $this->andWhere(['trash'=>'Default']);
    }

    public function active() {
        return $this->andWhere(['country_status'=>'Active']);
    }
}