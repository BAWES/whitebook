<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\CFormatter;

foreach ($model as $key => $value) { ?>

<div class="product_popup_signup">
<div class="product_popup_prod">
<span class="prod_popu">
<?= Url::to(Html::img(Yii::getAlias("@vendor_item_images_210/").$value['image_path'],['class'=>'item-img', 'style'=>'width:210px; height:208px;'])); ?>
</span>
<div class="desc_popup_cont">
<h4><?= $value['vendor_name']  ?></h4>
<h3><?= $value['item_name']  ?></h3>
<div class="text-center"><span class="borderslid"></span></div>
<h5>
	<?= CFormatter::asCurrency($value['item_price_per_unit'])  ?>		
</h5>
</div>
</div>
</div>
<div class="product_popup_signup_box">
<div class="product_popup_signup_log">
<form name="create_event" id="create_event">
<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<div class="form-group">
<input type="hidden" name="item_id" value="<?php echo $value['item_id'];?>" />
</div>

<?php if(!Yii::$app->user->isGuest){ ?>
<div class="form-group new_popup_common">
<div class="bs-docs-example">
    <select name="eventlist<?php echo $value['item_id'];?>" id="eventlist<?php echo $value['item_id'];?>">
        <option value=''>Select Event</option>
        <?php
        foreach($customer_events as $e) { ?>
        <option value="<?php echo $e['event_id'];?>"><?php echo $e['event_name'];?></option>
        <?php } ?>
    </select>
</div>
<div class="error" id="add_to_event_error<?php echo $value['item_id'];?>"></div>
</div>
<?php } ?>
<div class="error err-message" id="add_to_event_failure<?php echo $value['item_id'];?>" style="color:red;margin-bottom: 10px;"></div>
<div id="add_to_event_success<?php echo $value['item_id'];?>" style="color:red;margin-bottom: 10px;"></div>
<div class="event_loader" id="add_to_event_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader"></div>
<div class="buttons">
<div class="creat_evn_sig">
<button type="button" id="add_event_button" name="add_event_button" onclick="add_to_event(<?php echo $value['item_id'];?>);" class="btn btn-default" title="<?php echo Yii::t('frontend','Add to Event');?>"><?php echo Yii::t('frontend','Add to Event');?></button>
</div>
<span class="text-center forgotpwd"><a data-target="#EventModal" data-dismiss="modal" data-toggle="modal" title="<?php echo Yii::t('frontend','Create New Event');?>"  onclick="add_create_event(<?php echo  $value['item_id'];?>)" class="actionButtons" href="#"> <?php echo Yii::t('frontend','Create New Event');?></a></span>

</div>

</form>
</div>
</div>


<?php } ?>
