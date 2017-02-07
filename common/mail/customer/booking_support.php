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

        We need support for booking <a href="<?= Url::to(["browse/detail", 'slug' => $item['slug']], true); ?>"><?= $item->item_name ?></a>

        <br /> <br />

        <b>Name:</b> <?= $name ?> <br /><br />

        <b>Phone:</b> <?= $phone ?> <br /><br />
        
        <b>Email:</b> <?= $email ?> <br /><br />

    </td>
    <td width="20"></td>
</tr>