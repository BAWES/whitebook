<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[FeatureGroupItem]].
 *
 * @see FeatureGroupItem
 */
class FeatureGroupItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FeatureGroupItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FeatureGroupItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}