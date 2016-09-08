<?php
    
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Vendorpackages;
use common\models\Suborder;
use admin\models\Package;
use admin\models\Vendor;

class ReportController extends Controller
{

	public function actionCommission()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $group_by = Yii::$app->request->get('group_by');
        $vendor_id = Yii::$app->request->get('vendor_id'); 
        
        $groups = [
            'day' => 'Day',
            'week' => 'Week',
            'month' => 'Month',
            'year' => 'Year'
        ];

        $vendors = Vendor::find()
            ->where(['trash' => 'Default'])
            ->all();

        //result query  
        $query = Suborder::getReportQuery([
                'date_start' => $date_start,
                'date_end' => $date_end,
                'group_by' => $group_by,
                'vendor_id' => $vendor_id
            ]); 

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $query->count()]);

        // limit the query using the pagination and retrieve the result
        $result = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('commission', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'group_by' => $group_by,
            'vendor_id' => $vendor_id,
            'result' => $result,
            'pagination' => $pagination,
            'vendors' => $vendors,
            'groups' => $groups
        ]);
    }

    public function actionPackage()
    {
    	$date_start = Yii::$app->request->get('date_start');
    	$date_end = Yii::$app->request->get('date_end');
    	$group_by = Yii::$app->request->get('group_by');
    	$package_id = Yii::$app->request->get('package_id'); 
    	
    	$packages = Package::find()
    		->where(['package_status' => 'Active', 'trash' => 'Default'])
    		->all();

    	$groups = [
    		'day' => 'Day',
    		'week' => 'Week',
    		'month' => 'Month',
    		'year' => 'Year'
    	];
    	
    	//result query 	
    	$query = Vendorpackages::getReportQuery([
    			'date_start' => $date_start,
		    	'date_end' => $date_end,
		    	'group_by' => $group_by,
		    	'package_id' => $package_id
    		]);	

    	// create a pagination object with the total count
		$pagination = new Pagination(['totalCount' => $query->count()]);

		// limit the query using the pagination and retrieve the result
		$result = $query->offset($pagination->offset)
		    ->limit($pagination->limit)
		    ->asArray()
		    ->all();

        return $this->render('package', [
        	'date_start' => $date_start,
	    	'date_end' => $date_end,
	    	'group_by' => $group_by,
	    	'package_id' => $package_id,
	    	'packages' => $packages,
	    	'result' => $result,
	    	'pagination' => $pagination,
	    	'groups' => $groups
        ]);
    }
}