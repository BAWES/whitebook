<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorDraftOrderAlertEmails]].
 *
 * @see Booking
 */
class VendorDraftOrderAlertEmailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftOrderAlertEmails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftOrderAlertEmails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function draft($ID) {
        return $this->andWhere(['vendor_draft_id' => $ID]);
    }
}