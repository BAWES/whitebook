<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorDraft;

/**
 * VendorDraftSearch represents the model behind the search form about `common\models\VendorDraft`.
 */
class VendorDraftSearch extends VendorDraft
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_draft_id', 'vendor_id', 'created_by', 'modified_by', 'is_ready'], 'integer'],
            [['vendor_name', 'vendor_name_ar', 'vendor_return_policy', 'vendor_return_policy_ar', 'vendor_public_email', 'vendor_contact_name', 'vendor_contact_email', 'vendor_contact_number', 'vendor_contact_address', 'vendor_contact_address_ar', 'vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_emergency_contact_number', 'vendor_fax', 'vendor_logo_path', 'short_description', 'short_description_ar', 'vendor_website', 'vendor_facebook', 'vendor_facebook_text', 'vendor_twitter', 'vendor_twitter_text', 'vendor_instagram', 'vendor_instagram_text', 'vendor_youtube', 'vendor_youtube_text', 'created_datetime', 'modified_datetime', 'vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no', 'slug'], 'safe'],
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
        $query = VendorDraft::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vendor_draft_id' => $this->vendor_draft_id,
            'vendor_id' => $this->vendor_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'is_ready' => $this->is_ready,
        ]);

        $query->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
            ->andFilterWhere(['like', 'vendor_name_ar', $this->vendor_name_ar])
            ->andFilterWhere(['like', 'vendor_return_policy', $this->vendor_return_policy])
            ->andFilterWhere(['like', 'vendor_return_policy_ar', $this->vendor_return_policy_ar])
            ->andFilterWhere(['like', 'vendor_public_email', $this->vendor_public_email])
            ->andFilterWhere(['like', 'vendor_contact_name', $this->vendor_contact_name])
            ->andFilterWhere(['like', 'vendor_contact_email', $this->vendor_contact_email])
            ->andFilterWhere(['like', 'vendor_contact_number', $this->vendor_contact_number])
            ->andFilterWhere(['like', 'vendor_contact_address', $this->vendor_contact_address])
            ->andFilterWhere(['like', 'vendor_contact_address_ar', $this->vendor_contact_address_ar])
            ->andFilterWhere(['like', 'vendor_emergency_contact_name', $this->vendor_emergency_contact_name])
            ->andFilterWhere(['like', 'vendor_emergency_contact_email', $this->vendor_emergency_contact_email])
            ->andFilterWhere(['like', 'vendor_emergency_contact_number', $this->vendor_emergency_contact_number])
            ->andFilterWhere(['like', 'vendor_fax', $this->vendor_fax])
            ->andFilterWhere(['like', 'vendor_logo_path', $this->vendor_logo_path])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'short_description_ar', $this->short_description_ar])
            ->andFilterWhere(['like', 'vendor_website', $this->vendor_website])
            ->andFilterWhere(['like', 'vendor_facebook', $this->vendor_facebook])
            ->andFilterWhere(['like', 'vendor_facebook_text', $this->vendor_facebook_text])
            ->andFilterWhere(['like', 'vendor_twitter', $this->vendor_twitter])
            ->andFilterWhere(['like', 'vendor_twitter_text', $this->vendor_twitter_text])
            ->andFilterWhere(['like', 'vendor_instagram', $this->vendor_instagram])
            ->andFilterWhere(['like', 'vendor_instagram_text', $this->vendor_instagram_text])
            ->andFilterWhere(['like', 'vendor_youtube', $this->vendor_youtube])
            ->andFilterWhere(['like', 'vendor_youtube_text', $this->vendor_youtube_text])
            ->andFilterWhere(['like', 'vendor_bank_name', $this->vendor_bank_name])
            ->andFilterWhere(['like', 'vendor_bank_branch', $this->vendor_bank_branch])
            ->andFilterWhere(['like', 'vendor_account_no', $this->vendor_account_no])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
