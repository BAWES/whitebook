<?php
namespace backend\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\models\Admin;
use common\models\Vendor;
use common\models\Siteinfo;
use common\models\Smtp;

class MainComponent extends Component
{
   
    // Status Image title
    public function statusTitle($status)
    {
        if($status == 'Active')
        return 'Activate';
        return 'Deactivate';
    }
    // Status Image URL
    public static function statusImageurl($img_status)
    {
        if($img_status == 'Active')
        return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }

    // Vendor Item Gridview Status Filter
    public function Vendoritemstatus()
    {
        return $status = ['Yes' => 'Yes', 'Pending' => 'Pending','Rejected'=>'Rejected'];
    }

    //All Gridview Status Filter
    public function Activestatus()
    {
        return $status = ['Active' => 'Activate', 'Deactive' => 'Deactivate'];
    }

    public  function Priority()
    {
        return $status = ['Normal' => 'Normal', 'Super' => 'Super'];
    }

}
?>
