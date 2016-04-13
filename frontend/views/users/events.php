<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Users;
use common\models\Image;
$this->title = 'Events/Wishlist | Whitebook';
$type="sss";
if(isset($_GET['type']))
{
$type=$_GET['type'];
}
?>
<section id="inner_pages_white_back">
<div class="container paddng0">
<?php if(Yii::$app->params['CUSTOMER_ID']!='') { ?>
<!-- Events slider start -->
<?php require(__DIR__ . '/../product/events_slider.php'); ?>
<!-- Events slider end -->
<?php } ?>
<div class="events_content_part">
<div class="event_detials_common tab_section_event">
<div class="tab_sections">
<div id="exTab2">
<ul class="nav nav-tabs">
<li class="col-md-6 col-xs-6 padding0 first-event-tab <?php if($slug=='events'){echo 'active';}?>">
<a data-toggle="tab" href="#1" aria-expanded="false">EVENTS</a>
</li>
<li class="col-md-6 col-xs-6 padding0 second-event-tab <?php if($slug=='thingsilike'){echo 'active';}?>"><a data-toggle="tab" href="#2" aria-expanded="true"><span class="heart-icon">THINGS I LIKE</span></a>
</li>
</ul>

<div class="tab-content">
<!-- <div id="loader1" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>ajax-loader.gif" title="Loader"></div> -->
<div id="1" class="tab-pane <?php if($slug=='events'){echo 'active';}?>">
<div class="cat_events_items">
<div class="select_category_sec">
<div class="select_boxes">
<select class="selectpicker" data-style="btn-primary" id="customer_event_type" name="customer_event_type" style="display:none" >
<option value='all'>Select event type </option>
<?php
foreach ($customer_event_type as $key => $value) { ?>
<option value="<?= $value['event_type']; ?>"><?= $value['event_type']; ?></option>
<?php }  ?>
</select>
</div>
</div>
</div>
<div class="thinl_like_sectons" >

<ul class="thing_items" id="user_event_list">
	<?php
foreach ($customer_events as $key => $value) { ?>
<li>
<div class="delet_icons_new" onclick="deletefiltering1('<?php echo $value['event_id'];?>');"></div>
<a href="<?php echo Url::toRoute("/event-details/".$value['slug']); ?>" id="<?php echo $value['event_id'];?>" title="<?= $value['event_name']; ?>">

<div class="thing_inner_items">
<h3><?php if(strlen($value['event_name'])>12){echo substr($value['event_name'], 0, 12).' ...';}else{ echo$value['event_name'];} ?></h3>
<p><?= $value['event_date']; ?></p>
<p><?= $value['event_type']; ?><br/></p>
</div>
</a>
</li>
</a>
<?php }  ?>
<li>
<div class="thing_inner_items border_none <?php if($slug=='thingsilike'){echo 'active';}?>">
<a href="#" data-toggle="modal" data-target="#EventModal" title="">&nbsp;</a>
</div>
</li>
</ul>

</div>

</div>

<div id="2" class="tab-pane second_event <?php if($slug=='thingsilike'){echo 'active';}?>">
<a class="filter-link" id="filter-toggle" style="display:none;">Filter</a>
<div class="category_select_box panel-collapse collapse" id="collapseFilter"><form>

<div class="col-md-2 paddingleft0">
<div class="select_boxes">
<select class="selectpicker" id="loadcategory" data-style="btn-primary" style="display: none;">
<option value="">Select Category</option>
<?php
if(!empty($categorylist)){
foreach($categorylist as $c){?>
	 <option value="<?=$c['category_id'];?>"><?=$c['category_name'];?></option>
	 <?php } } ?>

</select>
</div>
</div>

<div class="col-md-2 paddingcommon">
<div class="select_boxes">
<select class="selectpicker" id="vendorlist" data-style="btn-primary" style="display: none;">
<option value=''>Select Vendor</option>
<?php
if(!empty($vendorlist)){
foreach($vendorlist as $v){?>
	 <option value="<?=$v['vendor_id'];?>"><?=$v['vendor_name'];?></option>
	 <?php } }?>
</select>
</div>
</div>
<div class="col-md-2 paddingcommon">
<div class="select_boxes">
<select class="selectpicker" id="avl_sale" data-style="btn-primary" style="display: none;">
<option value=''>Available for sale</option>
<option value="Yes">YES</option>
<option value="No">NO</option>
</select>
</div>
</div>
<div class="col-md-2 paddingcommon">
<div class="select_boxes">
<select class="selectpicker" id="loadthemes" data-style="btn-primary" style="display: none;">
<option value="">Select Themes</option>
<?php
if(!empty($themelist)){
foreach($themelist as $t){?>
	 <option value="<?=$t['theme_id'];?>"><?=$t['theme_name'];?></option>
	 <?php } }?>
</select>
</div>
</div>
<div class="col-md-2 paddingright0">
<div class="select_butons">
<button class="btn btn-warning" id="filter" name="filter" type="button" title="Filter" onClick="wishlistfilter()">Filter</button>
</div>
</div>
<div class="col-md-2 paddingright0">
<div class="select_butons">
<div class="clear_buttons_new">
<button class="btn btn-warning" id="clearfilter1" name="clearfilter1" type="button" title="clear filter" onClick="clearfiltering();">Clear</button>
</div>
</div>
</div>
</form>
</div>
<div id="search_data">
<div class="events_listing_inner">
<div class="events_listing new-event">
<div id="loader1" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>ajax-loader.gif" title="Loader"></div>
<ul id="wishlist">
<?php
$wishlist = Users::loadcustomerwishlist(Yii::$app->params['CUSTOMER_ID']);
foreach ($wishlist as $key => $value) {
?>
<li id="<?php echo $value['item_id'];?>">
<div class="events_items">
<div class="events_images">
<div class="hover_events">
<div class="pluse_cont"><a href="javascript:;" role="button" id="<?php echo $value['item_id'];?>" name="<?php echo $value['item_id'];?>" class=""   data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id'];?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend','ADD_EVENT');?>"></a></div>
<div class="delet_icons"><a href="javascript:;" title=""   onclick="remove_from_favourite(<?php echo $value['item_id'];?>)"onclick="remove_from_favourite(<?php echo $value['item_id'];?>)"></a></div>
</div>
<?php
$image = Image::find()->select('image_path')->where(['item_id'=>$value['item_id'],'module_type'=>'vendor_item', 'trash'=>'Default'])->asArray()->one();
?>
<?= Html::img(Yii::getAlias("@vendor_item_images_210/").$image['image_path'],['class'=>'item-img', 'style'=>'width:210px; height:208px;']); ?>
</div>
<div class="events_descrip">
<a title="<?= $value['item_name']; ?>" href="<?php echo Yii::$app->homeUrl; ?>/product/<?= $value['slug']; ?>"><?= $value['vendor_name']; ?>
<h3><?= $value['item_name']; ?></h3>
<p><?php if($value['item_price_per_unit'] !='') {echo $value['item_price_per_unit'].'.00 KD'; }else echo '-'; ?></p>
</a>
</div>
</div>
</li>
<?php } ?>
</ul>
</div>
</div>
<?php /* if(count($wishlist) > 20)
{ ?>
<div class="lode_more_buttons">
<button type="button" class="btn btn-danger" title="Load More">Load More</button>
</div>
<?php } */ ?>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>
</section>

<div class="modal fade" id="create_new_event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content  modal_member_login signup_poupu row">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<div class="text-center">
<span class="yellow_top"></span>
</div>
<h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend','CREATE_NEW_EVENT');?></h4>
</div>
<div class="modal-body">
<div class="row">
<div class="col-xs-8 col-xs-offset-2">
<div class="product_popup_signup_box">
<div class="product_popup_signup_log">
<form name="create_event" id="create_event">
<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<div class="form-group">
<input type="text" name="event_name" class="form-control required" id="event_name" placeholder="<?php echo Yii::t('frontend','enter_event_name');?>" title="<?php echo Yii::t('frontend','enter_event_name');?>">
</div>
<div class="form-group">
<div id="datetimepicker2" class="input-group date">
<input type="text" name="event_date" class="form-control required datetimepicker" placeholder="<?php echo Yii::t('frontend','choose_event_date');?>" title="<?php echo Yii::t('frontend','choose_event_date');?>">
<span class="input-group-addon">
<i class="flaticon-calendar189"></i>
</span>
</div>
<label for="event_date" class="error_validate"></label>
</div>
<div class="form-group new_popup_common">
<div class="bs-docs-example"><select class="selectpicker required" name="event_type" id="event_type" >
<?php foreach($event_type as $e) { ?>
<option value="<?php echo $e['type_name'];?>"><?php echo $e['type_name'];?></option>
<?php } ?>
</select>
</div>
</div>
<div class="eventErrorMsg" style="color:red;margin-bottom: 10px;"></div>
<div class="event_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>ajax-loader.gif" title="Loader"></div>
<div class="buttons">
<div class="creat_evn_sig">
<button type="button" onclick="submit_create_event_form('create_event')" class="btn btn-default" title="<?php echo Yii::t('frontend','CREATE_EVENT');?>"><?php echo Yii::t('frontend','CREATE_EVENT');?></button>
</div>
<div class="cancel_sig">
<input class="btn btn-default" data-dismiss="modal" type="button" value="<?php echo Yii::t('frontend','CANCEL');?>" title="<?php echo Yii::t('frontend','CANCEL');?>">
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


<?php if(count($customer_wishlist_count)>0) {
foreach($customer_wishlist as $f) {  ?>
<div class="modal fade" id="addevent<?php echo $f['item_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content  modal_member_login signup_poupu row">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<div class="text-center">
<span class="yellow_top"></span>
</div>
<h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend','u_r_adding');?></h4>
</div>
<div class="modal-body">
<div class="row">
<div class="col-xs-8 col-xs-offset-2">

<div class="product_popup_signup">
<div class="product_popup_prod">
<span class="prod_popu">
<a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>/sig_ban.png" alt=""/></a>
</span>
<div class="desc_popup_cont">
<h4><?php echo $f['vendor_name'];?></h4>
<h3><?php echo $f['item_name'];?></h3>
<div class="text-center"><span class="borderslid"></span></div>
<h5><?php echo number_format($f['item_price_per_unit'],2).CURRENCY_CODE;?></h5>
</div>
</div>
</div>
<div class="product_popup_signup_box">
<div class="product_popup_signup_log">
<div class="add_event_form">
<form name="add_event_<?php echo  $f['item_id'];?>" id="add_event_<?php echo  $f['item_id'];?>">
<input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<input type="hidden" name="item_id" value="<?php echo  $f['item_id'];?>" />
<div class="form-group new_popup_common">
<div class="bs-docs-example">
<select class="selectpicker required" id="event_exist_name_<?php echo $f['item_id'];?>" name="event_exist_name" data-style="btn-primary">
<?php foreach($customer_events as $e) { ?>
<option value="<?php echo $e['event_id'];?>"><?php echo $e['event_name'];?></option>
<?php } ?>
</select>
</div>
</div>
<div class="eventErrorMsg" style="color:red;margin-bottom: 10px;"></div>
<div class="event_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>ajax-loader.gif" title="Loader"></div>
<div class="buttons">
<div class="creat_evn_sig">
<button type="button" class="btn btn-default" title="<?php echo Yii::t('frontend','ADD_NOW');?>" onclick="submit_add_event(<?php echo $f['item_id'];?>)"><?php echo Yii::t('frontend','ADD_NOW');?></button>
</div>
<div class="cancel_sig">
<button type="button" onclick="show_create_event_form();" class="btn btn-default" title="<?php echo Yii::t('frontend','CREATE_NEW_EVENT');?>"><?php echo Yii::t('frontend','CREATE_NEW_EVENT');?></button>
</div>
</div>
</form>
</div>
<div class="create_event_form">
<form name="create_new_event_<?php echo  $f['item_id'];?>" id="create_new_event_<?php echo  $f['item_id'];?>" method="post">
<input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<input type="hidden" name="item_id" value="<?php echo  $f['item_id'];?>" />
<div class="form-group">
<input type="text" name="event_name" class="form-control required" placeholder="<?php echo Yii::t('frontend','enter_event_name');?>" title="<?php echo Yii::t('frontend','enter_event_name');?>">
</div>
<div class="form-group">
<div id="datetimepicker2" class="input-group date">
<input type="text" name="event_date" class="form-control required datetimepicker" placeholder="<?php echo Yii::t('frontend','choose_event_date');?>" title="<?php echo Yii::t('frontend','choose_event_date');?>">
<span class="input-group-addon">
<i class="flaticon-calendar189"></i>
</span>
</div>
<label for="event_date" class="error_validate"></label>
</div>
<div class="form-group new_popup_common">
<div class="bs-docs-example"><select class="selectpicker required" name="event_type" data-style="btn-primary">
<?php foreach($event_type as $e) { ?>
<option value="<?php echo $e['type_name'];?>"><?php echo $e['type_name'];?></option>
<?php } ?>
</select>
</div>
</div>
<div class="eventErrorMsg" style="color:red;margin-bottom: 10px;"></div>
<div class="event_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>ajax-loader.gif" title="Loader"></div>
<div class="buttons">
<div class="creat_evn_sig">
<button type="button" onclick="submit_create_new_event_form(<?php echo $f['item_id'];?>)" class="btn btn-default" title="<?php echo Yii::t('frontend','CREATE_EVENT');?>"><?php echo Yii::t('frontend','CREATE_EVENT');?></button>
</div>
<div class="cancel_sig">
<button type="button" onclick="show_add_event_form();" class="btn btn-default" title="Add to Existing Event"><?php echo Yii::t('frontend','Add to Existing');?></button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php } } ?>

<script>

	function events_by_type(val)
	{
		if(val!='')
		{
		window.location="<?php echo Yii::$app->homeUrl;?>/events.html?type="+val;
		}
	}
	/* BEGIN load vendor based on category */
	jQuery('#loadvendor').change(function()
	{
		var path = "<?= Url::toRoute('site/loadvendorlist'); ?>";

		jQuery.ajax({
		type:'POST',
		url: path,
		data:{cat_id: jQuery(this).val()},
		success:function(data)
		{
			jQuery('#vendorlist').append(data);
			jQuery('#vendorlist').selectpicker('refresh');
		}
		}).done(function() {
			jQuery('.events_listing ul li:nth-child(5n)').addClass("margin-rightnone");
		});
	});
	/* END load vendor based on category */
	/* BEGIN load themes based on category */

	jQuery('#customer_event_type').change(function()
	{
		if(jQuery(this).val()!=''){
			jQuery("#loader1").show();

			var path = "<?= Url::toRoute('site/loadeventlist'); ?>";
			jQuery.ajax({
				type:'POST',
				url: path,
				data:{event_name: jQuery(this).val()},
				success:function(data)
				{
					jQuery("#loader1").hide();
					jQuery('ul#user_event_list').html(data);
				}
			}).done(function() {
				jQuery('.thing_items li:nth-child(8n)').addClass("margin-rightnone");

			});
		}
	});
	/* END load themes based on category */
	/* BEGIN load themes based on category */
	function clearfiltering()
	{
		jQuery('#loadcategory').val('').trigger('change');
		jQuery('#vendorlist').val('').trigger('change');
		jQuery('#avl_sale').val('').trigger('change');
		jQuery('#loadthemes').val('').trigger('change');
		wishlistfilter();
	}

	function deletefiltering1(x)
	{
		var strconfirm = confirm("Are you sure you want to delete?");
		if (strconfirm == true)
		{
			if(x!=''){
				jQuery("#loader1").show();

				var path = "<?= Url::toRoute('site/deleteevent'); ?>";
				jQuery.ajax({
				type:'POST',
				url: path,
				data:{event_id:x},
				success:function(data)
				{
					jQuery("#oner").load("<?= Url::toRoute('/product/event-slider'); ?>");
					jQuery('a#'+x).parent('li').remove();
					jQuery("#loader1").hide();
					jQuery("#loader1").hide();
					jQuery('ul#user_event_list').html(data);
					jQuery('#login_success').modal('show');
					jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">Event remove from your event list</span>');
					window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
					//jQuery('#add_to_event_success'+x).html('Item add to your event list');
				}
				}).done(function() {
					jQuery('.thing_items li:nth-child(8n)').addClass("margin-rightnone");
				});
			}
		}
	}
	/* BEGIN load themes based on category */
	function wishlistfilter()
	{
		var c_id= jQuery('#loadcategory').val();
		var v_id= jQuery('#vendorlist').val();
		var a_id= jQuery('#avl_sale').val();
		var t_id= jQuery('#loadthemes').val();

		if (c_id == "" || v_id == "" || a_id == "" || t_id == "")
		{
			var path = "<?= Url::toRoute('/site/loadwishlist'); ?>";

			jQuery("#loader2").show();
			jQuery("body").css({'position':'relative','display':'inline-block'});

			jQuery.ajax({
				type:'POST',
				url: path,
				data:{c_id: jQuery('#loadcategory').val(),v_id: jQuery('#vendorlist').val(),a_id: jQuery('#avl_sale').val(),t_id: jQuery('#loadthemes').val()},
				success:function(data)
				{
					jQuery("#loader2").hide();
					jQuery("body").css({'position':'','display':''});
					jQuery('ul#wishlist').html(data);
				}
			}).done(function() {
				jQuery('#wishlist li:nth-child(5n)').addClass("margin-rightnone");
			});
		}
	}

	/* END load themes based on category */

	/* BEGIN ADD EVENT */
	 function addevent(item_id)
	{
	    jQuery.ajax({
	        type:'POST',
	        url:"<?= Url::toRoute('/product/addevent'); ?>",
	        data:{'item_id':item_id},
	        success:function(data)
	        {
	            jQuery('#addevent').html(data);
	            jQuery('#eventlist'+item_id).selectpicker('refresh');
	            jQuery('#add_to_event').modal('show');

	        }
	    });
	}

	/* END ADD EVENT */
	function remove_from_events1234(x)
	{
		alert(4);
	}


	/* BEGIN FILTER TOGGLE */
	 if (jQuery(window).width() < 991) {
	 		jQuery('#filter-toggle').show();
	 		jQuery('.category_select_box').hide();
	 	}
	jQuery('#filter-toggle').on('click',function(){
		jQuery('.category_select_box').toggle();
	});
	/* BEGIN FILTER TOGGLE */
	jQuery('.events_listing ul li:nth-child(5n)').addClass("margin-rightnone");
	jQuery('.thing_items li:nth-child(8n)').addClass("margin-rightnone");

</script>
