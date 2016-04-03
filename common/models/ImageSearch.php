<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Image;

/**
 * ImageSearch represents the model behind the search form about `backend\models\Image`.
 */
class ImageSearch extends Image
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'image_user_id', 'created_by', 'modified_by'], 'integer'],
            [['image_user_type', 'image_path', 'image_datetime', 'image_ip_address', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
            [['image_file_size', 'image_width', 'image_height'], 'number'],
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
        $query = Image::find();

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
            'image_id' => $this->image_id,
            'image_user_id' => $this->image_user_id,
            'image_file_size' => $this->image_file_size,
            'image_width' => $this->image_width,
            'image_height' => $this->image_height,
            'image_datetime' => $this->image_datetime,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'image_user_type', $this->image_user_type])
            ->andFilterWhere(['like', 'image_path', $this->image_path])
            ->andFilterWhere(['like', 'image_ip_address', $this->image_ip_address])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
