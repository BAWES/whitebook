<?php

namespace admin\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "whitebook_package".
 *
 * @property string $package_id
 * @property string $package_name
 * @property integer $package_max_number_of_listings
 * @property string $package_sales_commission
 * @property string $package_pricing
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property Vendor[] $vendors
 */
class Package extends \yii\db\ActiveRecord
{
	public $package_value;
    public $arr = array();
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['package_name','package_max_number_of_listings', 'package_sales_commission', 'package_pricing',], 'required'],
            [['package_name','package_max_number_of_listings','package_sales_commission', 'package_pricing',], 'required'],
            [['package_max_number_of_listings', 'created_by', 'modified_by'], 'integer'],
            [['package_sales_commission'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],

            [['package_pricing'], 'number'],
            [['trash'], 'string'],
            [['package_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'package_id' => 'Package ID',
            'package_name' => 'Package ',
            'package_max_number_of_listings' => 'Maximum no of List',
            'package_sales_commission' => 'Sales Commission ( % )',
            'package_pricing' => 'Pricing ',
            'package_type'=>'Package Period',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'package_value'=>'',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendors()
    {
        return $this->hasMany(Vendor::className(), ['package_id' => 'package_id']);
    }

        public static function statusImageurl($img_status)
	{
		if($img_status == 'Active')
		return \Yii::$app->params['appImageUrl'].'active.png';
		return \Yii::$app->params['appImageUrl'].'inactive.png';
	}


	public static function loadpackage()
	{
		$packages=Package::find()->where(['package_status' => 'Active','trash'=>'Default'])
		->all();
		$package=ArrayHelper::map($packages,'package_id','package_name');
		return $package;
	}

	public static function PackageData($pack_id)
	{
		if($pack_id){
		$package_data= Package::find()->where(['package_id' => $pack_id,'package_status' => 'Active'])->all();
		//return $package_data;
		return $package_data[0]['package_name'];
	}else {
		return '----';
	}
	}


	public static function loadpackageall()
	{
		$packages=Package::find()->where(['package_status' => 'Active','trash'=>'default'])->all();
		return $packages;
	}

    public static function loadpackageprice($pack_id)
    {
        $package_data= Package::find()->where(['package_id' => $pack_id,'package_status' => 'Active'])->all();
        foreach($package_data as $pack => $data)
        {
             return  $package_price = $data['package_pricing'];
        }

    }

    public static function packagecount($pack_id)
	{
		 return $package_data= Vendor::find()->where(['package_id' => $pack_id])->count();

	}
}
