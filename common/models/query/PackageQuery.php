<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[PackageQuery]].
 *
 * @see Booking
 */
class PackageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PackageQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PackageQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active() {
        return $this->andWhere(['status' => 1]);
    }

    public function package($id) {
        return $this->andWhere(['package_id' => $id]);
    }

}