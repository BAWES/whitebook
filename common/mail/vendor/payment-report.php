<tr>
    <td width="20"></td>
    <td style=" font:normal 14px/21px arial; color:#333333;">
        Hi <?= $vendor->vendor_name ?>,
    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">
        <br />
        <br />
        
        Payment report available for Payment #<?= $model->payment_id ?>. 

        <br />
        <br />

        <a href="<?= Yii::$app->urlManagerVendor->createUrl(['payments/detail', 'id' => $model->payment_id], true); ?>" style="background-color:#ff0000;border:1px solid #ff0000;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">  
            Download 
        </a>

        <br />
    </td>
    <td width="20"></td>
</tr>
