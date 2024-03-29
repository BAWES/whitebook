<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\AccessControl;

/**
* AccessControlSearch represents the model behind the search form about `common\models\Accesscontrol`.
*/
class AccessControlSearch extends AccessControl
{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['access_id', 'role_id', 'admin_id', 'created_by', 'modified_by'], 'integer'],
            //  [['created_datetime', 'modified_datetime'], 'safe'],
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
        $query = AccessControl::find()
            ->where(['=', 'default', '0'])
            ->groupBy('admin_id');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['access_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'access_id' => $this->access_id,
            'role_id' => $this->role_id,
            'admin_id' => $this->admin_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        return $dataProvider;
    }
}
