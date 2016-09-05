<form action="<?php echo $action; ?>" method="post" id="tap_form">

  <?= Yii::t('frontend', 'redirecting...'); ?>

  <input type="hidden" name="MEID" value="<?php echo $meid; ?>" />
  <input type="hidden" name="UName" value="<?php echo $uname; ?>" />
  <input type="hidden" name="PWD" value="<?php echo $pwd; ?>" />
  <input type="hidden" name="ItemName1" value="<?php echo $itemname1; ?>" />
  <input type="hidden" name="ItemQty1" value="1" />
  <input type="hidden" name="ItemPrice1" value="<?php echo $itemprice1; ?>" />
  <input type="hidden" name="CurrencyCode" value="<?php echo $currencycode; ?>" />
  <input type="hidden" name="OrdID" value="<?php echo $ordid; ?>" />
  <input type="hidden" name="CstEmail" value="<?php echo $cstemail; ?>" />
  <input type="hidden" name="CstFName" value="<?php echo $cstname; ?>" />
  <input type="hidden" name="CstMobile" value="<?php echo $cstmobile; ?>" />
  <input type="hidden" name="Cntry" value="<?php echo $cntry; ?>" />
  <input type="hidden" name="ReturnURL" value="<?php echo $returnurl; ?>" />
</form>

<script>
window.onload = function(){
 // document.getElementById('tap_form').submit();
}
</script>

