<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[Themes]].
 *
 * @see Booking
 */
class ThemesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Themes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Themes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function byThemeID($id) {
        return $this->where(['theme_id' => $id]);
    }
}