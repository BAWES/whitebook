<?php

namespace backend\models;
use yii\db\ActiveQuery;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Category;
use backend\models\SubCategory;

/**
 * CategorySearch represents the model behind the search form about `backend\models\Category`.
 */
class CategorySearch extends Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'parent_category_id', 'created_by', 'modified_by'], 'integer'],
            [['category_name', 'category_allow_sale', 'modified_datetime', 'trash'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		 $query = SubCategory::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->andwhere(['parent_category_id' => null])
		->orderBy('category_id');
		 $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['category_id'=>SORT_DESC]]
        ]);			
        
        $this->load($params);

        if (!$this->validate()) {
           return $dataProvider;
        }
        $query->andFilterWhere(['like', 'category_name', $this->category_name]);
        return $dataProvider;
    }
    
          public function subcategory_search($params)
    {
        $query = SubCategory::find()
        ->where(['!=', 'parent_category_id', 'NULL'])
        ->andwhere(['=', 'trash', 'Default'])
        ->andwhere(['=', 'category_level', '1'])
		->orderBy(['parent_category_id'=>SORT_ASC]);
            $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['parent_category_id'=>SORT_ASC,'category_id' =>SORT_DESC]]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'category_name', $this->category_name]);
        $query->andFilterWhere(['=', 'parent_category_id', $this->parent_category_id]);
        return $dataProvider;
    }
    
    public function childcategory_search($params)
    {
        $query = ChildCategory::find()
        ->where(['!=', 'parent_category_id', 'NULL'])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->andwhere(['=', 'category_level', '2'])
		->orderBy(['parent_category_id'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['parent_category_id'=>SORT_ASC,'category_id' =>SORT_DESC]]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'category_name', $this->category_name]);
        $query->andFilterWhere(['=', 'parent_category_id', $this->parent_category_id]);        
        return $dataProvider;
    }
}
