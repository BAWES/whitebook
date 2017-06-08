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
        return $this->andWhere(['{{%category}}.trash' => "Default"]);
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

    public function slug($slug) {
        return $this->andWhere(['slug' => $slug]);
    }

    public function topLevel() {
        return $this->andWhere([
            '{{%category_path}}.level' => 0
        ]);
    }

    public function categoryPathLevel($level) {
        return $this->andWhere([
            '{{%category_path}}.level' => $level
        ]);
    }

    public function topLevelCategory() {
        return $this->andWhere(['category_level' =>0]);
    }

    public function joinCategoryPath() {
        return $this->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id');
    }

    public function category($id) {
        return $this->andWhere(['category_id' => $id]);
    }

    public function notNullCategory() {
        return $this->andwhere(['!=', 'parent_category_id', 'null']);
    }

}