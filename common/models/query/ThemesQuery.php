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

    public function theme($id) {
        return $this->andWhere(['theme_id' => $id]);
    }

    public function active() {
        return $this->andWhere([
                'theme_status' => 'Active',
                'trash' => 'Default'
            ]);
    }

    public function defaultCategory() {
        return $this->andWhere(['trash' => 'Default']);
    }
}