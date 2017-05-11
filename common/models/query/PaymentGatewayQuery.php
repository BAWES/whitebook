<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[PaymentGatewayQuery]].
 *
 * @see Booking
 */
class PaymentGatewayQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PaymentGatewayQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PaymentGatewayQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active() {
        return $this->andWhere(['status' => 1]);
    }

    public function getaway($code) {
        return $this->andWhere(['code' => $code]);
    }
}