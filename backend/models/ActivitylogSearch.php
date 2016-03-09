<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Activitylog;

/**
 * ActivitylogSearch represents the model behind the search form about `backend\models\Activitylog`.
 */
class ActivitylogSearch extends Activitylog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'log_user_id'], 'integer'],
            [['log_user_type', 'log_action', 'log_username','log_datetime'], 'safe'],
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
        $query = Activitylog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['log_id'=>SORT_DESC]],
			'pagination' =>[
				'pageSize'=> 40,
				],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'log_user_id' => $this->log_user_id,
            'log_datetime' => $this->log_datetime,
        ]);

        $query->andFilterWhere(['like', 'log_user_type', $this->log_user_type])
            ->andFilterWhere(['like', 'log_action', $this->log_action]);

        return $dataProvider;
    }
}
