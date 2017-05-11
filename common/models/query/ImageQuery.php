<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[ImageQuery]].
 *
 * @see Booking
 */
class ImageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ImageQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ImageQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($itemID){
        return $this->andWhere('item_id = :id', [':id' => $itemID]);
    }

    public function module($type) {
        return $this->andWhere('module_type = :status', [':status' => $type]);
    }
}