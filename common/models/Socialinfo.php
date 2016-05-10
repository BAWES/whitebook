<?php
namespace common\models;

use Yii;
use yii\helpers\Setdateformat;

/**
 * This is the model class for table "store_social_info".
 *
 * @property integer $store_social_id
 * @property integer $store_id
 * @property string $store_facebook_share
 * @property string $store_twitter_share
 * @property string $store_google_share
 * @property string $store_linkedin_share
 * @property string $google_analytics
 * @property string $live_script
 */
class Socialinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'store_facebook_share', 'store_twitter_share', 'store_google_share', 'store_linkedin_share', 'google_analytics', 'live_script'], 'required'],
            [['store_id'], 'integer'],
            [['store_facebook_share', 'store_twitter_share', 'store_google_share', 'store_linkedin_share', 'google_analytics', 'live_script'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_social_id' => 'Store Social ID',
            'store_id' => 'Store ID',
            'store_facebook_share' => 'Store Facebook Share',
            'store_twitter_share' => 'Store Twitter Share',
            'store_google_share' => 'Store Google Share',
            'store_linkedin_share' => 'Store Linkedin Share',
            'google_analytics' => 'Google Analytics',
            'live_script' => 'Live Script',
        ];
    }

   /* 
    *
    *   To save created, modified user & date time 
    */ 
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
           $this->created_datetime = Setdateformat::convert(time(),'datetime');
           $this->created_by = \Yii::$app->user->identity->id;
        } 
        else {
           $this->modified_datetime = Setdateformat::convert(time(),'datetime');
           $this->modified_by = \Yii::$app->user->identity->id;
        }
           return parent::beforeSave($insert);
    }


    // loading info to frontend
    public static function socialinformation()
    {
        $model = Socialinfo::find()->all();
        foreach($model as $key=>$val)
        {
             return $val;
        }        
    }    
}
