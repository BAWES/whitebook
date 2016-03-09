<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventinviteesSearch represents the model behind the search form about `frontend\models\Eventinvitees`.
 */
class EventinviteesSearch extends Eventinvitees
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invitees_id', 'event_id', 'created_by', 'modified_by'], 'integer'],
            [['name', 'email', 'phone_number', 'created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $invitee_name, $event_id)
    {
        //echo $event_id;die;
        $query = Eventinvitees::find()
        ->where(['LIKE', 'name', $invitee_name])
        ->orwhere(['LIKE', 'email', $invitee_name])
        ->orwhere(['LIKE', 'phone_number', $invitee_name])
        ->andwhere(['=', 'event_id', $event_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'invitees_id' => $this->invitees_id,
            'event_id' => $this->event_id,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number]);

        return $dataProvider;
    }

    public function loadsearch($params, $slug)
    {
        $event_details = Yii::$app->db->createCommand('SELECT event_id FROM whitebook_events where slug = "'.$slug.'"')->queryAll();

        $query = Eventinvitees::find()
        ->andwhere(['event_id' => $event_details[0]['event_id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'invitees_id' => $this->invitees_id,
            'event_id' => $this->event_id,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number]);

        return $dataProvider;
    }
}
