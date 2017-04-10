<?php

namespace common\models\query;
use yii\db\Expression;
use Yii;
/**
 * This is the ActiveQuery class for [[PriorityItem]].
 *
 * @see Booking
 */
class PriorityItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PriorityItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PriorityItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($id) {
        return $this->andWhere(new Expression('FIND_IN_SET(:item_id, item_id)'))->addParams([':item_id' => $id]);
    }
}