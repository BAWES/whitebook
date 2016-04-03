<?php
use yii\helpers\Html;
use common\models\Vendor; 

$this->title = 'Payment status';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
              <div class="grid simple transaction_new">
                <div class="grid-body no-border"> <br>
                  <div class="row transact_align">
<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" col="2">
  <tbody><tr>
    <td colspan="2" align="left" class="msg"><strong class="text">Transaction was successful. Transaction Details (from Merchant Notification Message)</strong></td>
    </tr>
  <tr>
    <td  class="tdfixed">Payment ID :</td>
    <td  class="tdwhite"><?= $PaymentID; ?></td>
  </tr>
  <tr>
    <td class="tdfixed">Post Date :</td>
    <td class="tdwhite"><?= $PostDate;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Result Code :</td>
    <td class="tdwhite"><?= $Result;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Transaction ID :</td>
    <td class="tdwhite"><?= $TranID;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Auth :</td>
    <td class="tdwhite"><?= $Auth;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Track ID :</td>
    <td class="tdwhite"><?= $TrackID;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Ref no :</td>
    <td class="tdwhite"><?= $Ref;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Package name :</td>
    <td class="tdwhite"><?= $UDF2;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Package value :</td>
    <td class="tdwhite"><?= $UDF3;?> <?php echo CURRENCY; ?></td>
  </tr>
  <tr>
    <td class="tdfixed">Package days :</td>
    <td class="tdwhite"><?= $UDF4;?></td>
  </tr>
  <tr>
    <td class="tdfixed">Package maximum listings :</td>
    <td class="tdwhite"><?= $UDF5;?></td>
  </tr>
  <?php
	$packageenddate=Vendor::getVendor('package_end_date');
	$date = date_create($packageenddate);
	$enddate = date_format($date, 'd-m-Y');
  ?>
  <tr>
    <td class="tdfixed">Package expiry date :</td>
    <td class="tdwhite"><?= $enddate;?></td>
  </tr>
</tbody></table>
					</div>
				</div>
			</div>
		</div>
<style>
.transaction_new .transact_align table{border: 0px solid #efefef;background:#fff;}
.transaction_new .transact_align table .msg .text{color: #000000;display: inline-block;font-size: 13pt;
line-height: 35pt;
font-weight: 700;width: 100%;}
.transaction_new .transact_align table .tdfixed{padding-left:10px;color: #808080;font-size: 11pt;
line-height: 15pt;
font-weight: 500;padding-bottom: 10px; width: 20%;}

.transaction_new .transact_align table .tdwhite{padding-left:10px;color: #808080;font-size: 11pt;
line-height: 15pt;
font-weight: 500;padding-bottom: 10px}
</style>
