<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $message string */

?>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">
        
        Hi <?= $vendor->vendor_contact_name ?>,

        <br /><br />

        Your item (<?= $model->item_name ?>) got rejected because of 

        <br /><br />

        "<?= $reason ?>"

        <br /><br />

        Please correct it and resubmit for approval. 
        
    </td>
    <td width="20"></td>
</tr>
