<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use admin\models\AddressType;
use admin\models\AddresstypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\base;
use yii\data\ActiveDataProvider;

/**
 * AddresstypeSearch represents the model behind the search form about `common\models\AddressType`.
 */
class AddresstypeSearch extends AddressType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name'], 'safe'],
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
		$query = AddressType::find()
        ->where(['!=', 'trash', 'Deleted'])
		->orderBy('type_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['type_id'=>SORT_DESC]]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
             $query->where('trash'!="Deleted");
            return $dataProvider;
        }
         $query->andFilterWhere(['like', 'type_name', $this->type_name]);
            return $dataProvider;
    }
}
