<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminSearch represents the model behind the search form about `backend\models\Admin`.
 */
class AdminSearch extends Admin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role_id'], 'integer'],
            [['admin_name', 'admin_email', 'admin_password', 'admin_status'], 'safe'],
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
        $query = Admin::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role_id' => $this->role_id,
        ]);

        $query->andFilterWhere(['like', 'admin_name', $this->admin_name])
            ->andFilterWhere(['like', 'admin_email', $this->admin_email])
            ->andFilterWhere(['like', 'admin_password', $this->admin_password])
            ->andFilterWhere(['like', 'admin_status', $this->admin_status]);

        return $dataProvider;
    }
}
