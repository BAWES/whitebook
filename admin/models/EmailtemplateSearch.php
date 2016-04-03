<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Emailtemplate;

/**
 * EmailtemplateSearch represents the model behind the search form about `app\models\Emailtemplate`.
 */
class EmailtemplateSearch extends Emailtemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_template_id'], 'integer'],
            [['email_title', 'email_subject', 'email_content'], 'safe'],
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
        $query = Emailtemplate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['email_template_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'email_template_id' => $this->email_template_id,
        ]);

        $query->andFilterWhere(['like', 'email_title', $this->email_title])
            ->andFilterWhere(['like', 'email_subject', $this->email_subject])
            ->andFilterWhere(['like', 'email_content', $this->email_content]);

        return $dataProvider;
    }
}
