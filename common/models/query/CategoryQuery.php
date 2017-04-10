<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[Category]].
 *
 * @see Booking
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function defaultCategories()
    {
        return $this->andWhere(['trash'=>"Default"]);
    }

    public function allParents()
    {
        return $this->andWhere('(parent_category_id IS NULL or parent_category_id = 0)');
    }

    public function orderByExpression() {
        return $this->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'));
    }

    public function nonEmptySlug() {
        return $this->andWhere(['!=', 'slug', '']);
    }

}