<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Siteinfo extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%siteinfo}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['home_slider_alias'], 'required'],
            [['home_slider_alias', 'super_admin_role_id'],'required', 'on' => 'update']
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['home_slider_alias', 'super_admin_role_id'];//Scenario Values Only Accepted
        return $scenarios;
    }

    /*
    *
    *   To save created, modified user & date time
    */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_name' => 'App Name',
            'app_desc' => 'App Description',
            'meta_keyword' => 'Meta Keyword',
            'meta_desc' => 'Meta Description',
            'email_id' => 'email',
            'phone_number' => 'Phone Number',
            'site_location' => 'Site Location',
            'site_copyright' => 'Site Copyright',
            'facebook_key' => 'Facebook Key',
            'commision' => 'Commision percentage',
            'currency_symbol'=>'Currency symbol',

        ];
    }

    // Datas using frontend
    public static function siteinformation()
    {
        $model = Siteinfo::find()->all();

        foreach($model as $key=>$val)
        {
            return $val;
        }
    }

}
