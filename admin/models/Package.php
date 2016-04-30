<?php

namespace admin\models;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\Vendor;

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
class Package extends \common\models\Package
{

    public static function statusImageurl($img_status)
	{			
		if($img_status == 'Active')		
		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
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

	

	
	
	

