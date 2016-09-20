<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user string */
/* @var $message string */

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

        Order ID : #<?= $order_id ?>

        <br />

        Sub order ID : #<?= $sub_order_id ?>

        <br />

        Vendor : <?= $vendor ?>

        <br />
        <br />

        <?= $message; ?>
    </td>
    <td width="20"></td>
</tr>
