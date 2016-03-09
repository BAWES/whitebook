<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Smtp;

/**
 * SmtpSearch represents the model behind the search form about `app\models\Smtp`.
 */
class SmtpSearch extends Smtp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['smtp_host', 'smtp_username', 'smtp_password', 'smtp_port', 'transport_layer_security'], 'safe'],
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
        $query = Smtp::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'smtp' => $this->smtp,
        ]);

        $query->andFilterWhere(['like', 'smtp_host', $this->smtp_host])
            ->andFilterWhere(['like', 'smtp_username', $this->smtp_username])
            ->andFilterWhere(['like', 'smtp_password', $this->smtp_password])
            ->andFilterWhere(['like', 'smtp_port', $this->smtp_port])
            ->andFilterWhere(['like', 'transport_layer_security', $this->transport_layer_security]);

        return $dataProvider;
    }
}
