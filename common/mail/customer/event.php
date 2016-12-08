<?php

use yii\helpers\Url;

?>
<tr>
    <td width="20"></td>
    <td style=" font:normal 14px/21px arial; color:#333333;">
        Hi,

    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">

        Your friend <b><?= $customer->customer_name.' '.$customer->customer_last_name  ?></b> have share event with you. 

        <br />
        <br />

        <a href="<?= Url::to(['events/public', 'token' => $event->token], true) ?>">
            <?= Url::to(['events/public', 'token' => $event->token], true) ?>
        </a>
    </td>
    <td width="20"></td>
</tr>
