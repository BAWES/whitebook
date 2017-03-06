<?php
    
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Booking;
use admin\models\Vendor;
use admin\models\AccessControlList;

class ReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => AccessControlList::can()
                    ],
                ],
            ],            
        ];
    }

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
        $query = Booking::getReportQuery([
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
}