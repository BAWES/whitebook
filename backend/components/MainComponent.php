<?php
namespace backend\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use backend\models\Admin;
use backend\models\Vendor;
use backend\models\Siteinfo;
use backend\models\Smtp;

class MainComponent extends Component
{
public function sendmail($to,$subject,$content = false,$message = false,$template = false )
{
$from = 'a.mariyappan88@gmail.com';
//add headers
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'To: admin@whitebook.com' . "\r\n";
$headers .= 'From: admin@whitebook.com' . "\r\n";

if($template == 'USER-REGISTER')
{
$siteinfo = Siteinfo::find()->asArray()->all();
$subject="Customer registered.";
//$msg="New customer registered our whitebook.";
$content = '<html>
<head>
<title>User Register</title>
</head>
<body style="margin:0; padding:0;">
<table border="0" style="width:600px; background:#fff; border:none; border-radius:6px; border-left:1px solid #ccc;border-right:1px solid #ccc;" cellpadding="0" cellspacing="0">
<!-- header_content start -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff; height:110px; border-bottom:2px solid #ccc;">
<td width="20"></td>
<td width="140"><img style="height:33px; width:233px;margin-left:125px;" src="http://www.demositeapp.com/backend/web/uploads/app_img/logo.png" alt=""/></td>
<td width="20"></td>
</table>
</td>
</tr>
<!-- header_content end -->
<!-- middle_content_content start -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff;">
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/21px arial; color:#333333;">
Hi Admin ,

</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 15px arial; color:#333333;">
'.$message.'						
</td>
<td width="20"></td>
</tr>
<tr>
<td width="20"></td>
<td>
<table cellspacing="0" cellpadding="0">
<tr>
<td width="130"></td>
<td width="320px">
</td>
<td width="130"></td>
</tr>
</table>
</td>
<td width="20"></td>
</tr>
<tr height="30"></tr>
</table>
</td>
</tr>
<!-- middle_content_content end -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff;">
<tbody><tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/21px arial; color:#333333;">
For any further assistance or queries, please email us at '.$siteinfo[0]['email_id'].' or call us on
</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 20px arial; color:#666;">
'.$siteinfo[0]['phone_number'].'
</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
</tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/23px arial; color:#666;">
Best Regards,<br>Whitebook Team
</td>
<td width="20"></td>
</tr>
<tr height="20"></tr>
</tbody></table>
</td>
</tr>
</table>
</body>
</html>'; 		
$send = Yii::$app->mailer->compose()
->setFrom($from)
->setTo($to)
->setSubject($subject)
->setHtmlBody($content)
->send();
}
else if($template == 'FORGOT-PASSWORD')
{
$siteinfo = Siteinfo::find()->asArray()->all();
//$subject="Customer registered.";
//$msg="New customer registered our whitebook.";
$content = '<html>
<head>
<title>User forgot login details</title>
</head>
<body style="margin:0; padding:0;">
<table border="0" style="width:600px; background:#fff; border:none; border-radius:6px; border-left:1px solid #ccc;border-right:1px solid #ccc;" cellpadding="0" cellspacing="0">
<!-- header_content start -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff; height:110px; border-bottom:2px solid #ccc;">
<td width="20"></td>
<td width="140"><img style="height:33px; width:233px;margin-left:125px;" src="http://www.demositeapp.com/backend/web/uploads/app_img/logo.png" alt=""/></td>
<td width="20"></td>
</table>
</td>
</tr>
<!-- header_content end -->
<!-- middle_content_content start -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff;">
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/21px arial; color:#333333;">
Hi customer,

</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 15px arial; color:#333333;">
'.$message.'						
</td>
<td width="20"></td>
</tr>
<tr>
<td width="20"></td>
<td>
<table cellspacing="0" cellpadding="0">
<tr>
<td width="130"></td>
<td width="320px">
</td>
<td width="130"></td>
</tr>
</table>
</td>
<td width="20"></td>
</tr>
<tr height="30"></tr>
</table>
</td>
</tr>
<!-- middle_content_content end -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff;">
<tbody><tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/21px arial; color:#333333;">
For any further assistance or queries, please email us at '.$siteinfo[0]['email_id'].' or call us on
</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 20px arial; color:#666;">
'.$siteinfo[0]['phone_number'].'
</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
</tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/23px arial; color:#666;">
Best Regards,<br>Whitebook Team
</td>
<td width="20"></td>
</tr>
<tr height="20"></tr>
</tbody></table>
</td>
</tr>
</table>
</body>
</html>'; 
$send = Yii::$app->mailer->compose()
->setFrom($from)
->setTo($to)
->setSubject($subject)
->setHtmlBody($content)
->send();
}
else if($template == 'PENDING-ITEMS')
{
$siteinfo = Siteinfo::find()->asArray()->all();		
//$msg="PENDING-ITEMS";
$content = '<html>
<head>
<title>User Register</title>
</head>
<body style="margin:0; padding:0;">
<table border="0" style="width:600px; background:#fff; border:none; border-radius:6px; border-left:1px solid #ccc;border-right:1px solid #ccc;" cellpadding="0" cellspacing="0">
<!-- header_content start -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff; height:110px; border-bottom:2px solid #ccc;">
<td width="20"></td>
<td width="140"><img style="height:33px; width:233px;margin-left:125px;" src="http://www.demositeapp.com/backend/web/uploads/app_img/logo.png" alt=""/></td>
<td width="20"></td>
</table>
</td>
</tr>
<!-- header_content end -->
<!-- middle_content_content start -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff;">
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/21px arial; color:#333333;">
Hi Admin ,

</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 15px arial; color:#333333;">
'.$message.'						
</td>
<td width="20"></td>
</tr>
<tr>
<td width="20"></td>
<td>
<table cellspacing="0" cellpadding="0">
<tr>
<td width="130"></td>
<td width="320px">
</td>
<td width="130"></td>
</tr>
</table>
</td>
<td width="20"></td>
</tr>
<tr height="30"></tr>
</table>
</td>
</tr>
<!-- middle_content_content end -->
<tr>
<td valign="top">
<table style="width:600px; background:#fff;">
<tbody><tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/21px arial; color:#333333;">
For any further assistance or queries, please email us at '.$siteinfo[0]['email_id'].' or call us on
</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
<td style=" font:normal 20px arial; color:#666;">
'.$siteinfo[0]['phone_number'].'
</td>
<td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
<td width="20"></td>
</tr>
<tr>
<td width="20"></td>
<td style=" font:normal 14px/23px arial; color:#666;">
Best Regards,<br>Whitebook Team
</td>
<td width="20"></td>
</tr>
<tr height="20"></tr>
</tbody></table>
</td>
</tr>
</table>
</body>
</html>'; 		
$send = Yii::$app->mailer->compose()
->setFrom($from)
->setTo($to)
->setSubject($subject)
->setHtmlBody($content)
->send();
}
} 
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
return \Yii::$app->params['appImageUrl'].'active.png';
return \Yii::$app->params['appImageUrl'].'inactive.png';
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

public function Activity($user_type,$user_action,$management = '',$management_id = '')
{
if($user_type !='')
{
if($user_type == 'Admin') {	 $user_id= Admin::getAdmin('id');  $username = Admin::getAdmin('admin_name');}
if($user_type == 'Vendor') {	$user_id= Vendor::getVendor('id'); $username = Vendor::getVendor('vendor_name');}			

$template = $username.' '.$user_action;
$time=date("Y-m-d h:i:s");
$ip = Yii::$app->getRequest()->getUserIP();
$command = \Yii::$app->DB->createCommand(
'INSERT INTO `whitebook_activity_log`(`log_user_id`,`log_username`,`log_user_type`,`management`,`management_id`,`log_datetime`,`log_action`,`log_ip`) 
VALUES ("'.$user_id.'","'.$username.'","'.$user_type.'","'.$management.'","'.$management_id.'","'.$time.'","'.$template.'","'.$ip.'")');			
$command->execute();
}
}
}
?>
