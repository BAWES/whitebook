<?php
    
namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Booking;
use admin\models\Vendor;
use admin\models\AccessControlList;
use \mPDF;

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
            ->defaultVendor()
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

    /**
     * Display form to download booking report 
     */
    public function actionBooking()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $vendor_id = Yii::$app->request->get('vendor_id'); 
        
        $vendors = Vendor::find()
            ->where(['trash' => 'Default'])
            ->all();

        return $this->render('booking_form', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'vendor_id' => $vendor_id,
            'vendors' => $vendors
        ]);
    }

    /**
     * Download booking report 
     * @params date_start DATE 
     * @params date_end DATE 
     * @params vendor_id INTEGER 
     * @return mix 
     */
    public function actionBookingReport()
    {
        $this->layout = 'pdf';

        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $vendor_id = Yii::$app->request->get('vendor_id'); 

        $query = Booking::find()
            ->joinVendorPayment();

        $implode = [];

        $implode[] = '{{%booking}}.booking_status = '.Booking::STATUS_ACCEPTED;

        if($date_start)
            $implode[] = 'DATE({{%booking}}.created_datetime) >= DATE("'.$date_start.'")';

        if($date_end)
            $implode[] = 'DATE({{%booking}}.created_datetime) <= DATE("'.$date_end.'")';

        if($vendor_id)
            $implode[] = '{{%booking}}.vendor_id = "'.$vendor_id.'"';

        if($implode)
            $query->where(implode(' AND ', $implode));

        $bookings = $query    
            ->all();

        $orders_by_payment_methods = $query
            ->select('count({{%booking}}.booking_id) as total, sum({{%booking}}.total_with_delivery) as total_sale, {{%booking}}.payment_method')
            ->groupBy('payment_method')
            ->asArray()
            ->all();

        $content = $this->render('booking_report', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'bookings' => $bookings,
            'orders_by_payment_methods' => $orders_by_payment_methods
        ]);

        $stylesheet = file_get_contents(Url::to('@web/themes/default/css/pdf.css', true));

        $prefix = Yii::getAlias('@runtime/mpdf') . DIRECTORY_SEPARATOR;
        
        define('_MPDF_TEMP_PATH', "{$prefix}tmp");
        define('_MPDF_TTFONTDATAPATH', "{$prefix}ttfontdata");

        $mpdf = new mPDF();
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($content);
        $mpdf->Output();        
    }
}