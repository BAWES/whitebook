<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Siteinfo;

/**
 * SiteinfoSearch represents the model behind the search form about `app\models\Siteinfo`.
 */
class SiteinfoSearch extends Siteinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'site_country', 'site_state', 'site_city', 'site_location', 'trial_days', 'paypal_payment_mode', 'pagination_per_page', 'show_map'], 'integer'],
            [['app_name', 'app_desc', 'meta_keyword', 'meta_desc', 'email_id', 'phone_number', 'site_currency_symbol', 'site_currency_code', 'site_copyright', 'site_logo', 'email_site_logo', 'site_header_logo', 'site_favicon', 'site_noimage', 'paypal_username', 'paypal_password', 'paypal_signature', 'facebook_key', 'facebook_secret_key', 'facebook_share_url', 'twitter_share_url', 'google_share_url', 'linkedin_share_url', 'google_analytics', 'live_script', 'updated_date'], 'safe'],
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
        $query = Siteinfo::find();

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
            'site_country' => $this->site_country,
            'site_state' => $this->site_state,
            'site_city' => $this->site_city,
            'site_location' => $this->site_location,
            'trial_days' => $this->trial_days,
            'paypal_payment_mode' => $this->paypal_payment_mode,
            'pagination_per_page' => $this->pagination_per_page,
            'show_map' => $this->show_map,
            'updated_date' => $this->updated_date,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'app_desc', $this->app_desc])
            ->andFilterWhere(['like', 'meta_keyword', $this->meta_keyword])
            ->andFilterWhere(['like', 'meta_desc', $this->meta_desc])
            ->andFilterWhere(['like', 'email_id', $this->email_id])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'site_currency_symbol', $this->site_currency_symbol])
            ->andFilterWhere(['like', 'site_currency_code', $this->site_currency_code])
            ->andFilterWhere(['like', 'site_copyright', $this->site_copyright])
            ->andFilterWhere(['like', 'site_logo', $this->site_logo])
            ->andFilterWhere(['like', 'email_site_logo', $this->email_site_logo])
            ->andFilterWhere(['like', 'site_header_logo', $this->site_header_logo])
            ->andFilterWhere(['like', 'site_favicon', $this->site_favicon])
            ->andFilterWhere(['like', 'site_noimage', $this->site_noimage])
            ->andFilterWhere(['like', 'paypal_username', $this->paypal_username])
            ->andFilterWhere(['like', 'paypal_password', $this->paypal_password])
            ->andFilterWhere(['like', 'paypal_signature', $this->paypal_signature])
            ->andFilterWhere(['like', 'facebook_key', $this->facebook_key])
            ->andFilterWhere(['like', 'facebook_secret_key', $this->facebook_secret_key])
            ->andFilterWhere(['like', 'facebook_share_url', $this->facebook_share_url])
            ->andFilterWhere(['like', 'twitter_share_url', $this->twitter_share_url])
            ->andFilterWhere(['like', 'google_share_url', $this->google_share_url])
            ->andFilterWhere(['like', 'linkedin_share_url', $this->linkedin_share_url])
            ->andFilterWhere(['like', 'google_analytics', $this->google_analytics])
            ->andFilterWhere(['like', 'live_script', $this->live_script]);

        return $dataProvider;
    }
}
