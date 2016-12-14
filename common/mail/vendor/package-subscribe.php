<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user string */
/* @var $vendorEmail string */
/* @var $vendorPassword string */

?>
<tr>
    <td width="20"></td>
    <td style=" font:normal 14px/21px arial; color:#333333;">
        Hi <?= $user; ?>,

    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">
        Admin created your username password. You can access account.
        <br/>
        User Id : <?= $vendorEmail ?>  <br/>
        Password : <?= $vendorPassword ?>
    </td>
    <td width="20"></td>
</tr>
