<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Users;
use common\models\Image;
use common\components\CFormatter;

$this->title = 'Events/Wishlist | Whitebook';
?>

	<section id="inner_pages_sections">
		<div class="container">
			<div class="title_main">
				<h1><?php echo Yii::t('frontend','Events'); ?></h1>
			</div>
			<div class="account_setings_sections">
				<?=$this->render('/users/_sidebar_menu');?>
				<div class="col-md-9 border-left">
					<div class="accont_informations">
						<?= \yii\grid\GridView::widget([
							'dataProvider' => $provider,
							'summary' => '',
							'columns' => [
								'event_name',
								'event_date',
								'event_type',
								'created_datetime',
								[
									'class' => 'yii\grid\ActionColumn',
									'header'=>'Action',
									'contentOptions' => ['class' => 'text-center'],
									'template' => '{view} {delete}',
									'buttons' => [
										'view' => function ($url, $model) {
											$url = Url::to(['events/detail','slug'=>$model['slug']],true);
											return  Html::a('<span class="fa fa-search"></span> &nbsp;View / Update', $url,
												[ 'title' => Yii::t('app', 'View'), 'class'=>'btn btn-primary btn-xs', ]) ;
										},
										'delete' => function ($url, $model) {
											$url = Url::to(['events/delete-event','id'=>$model['event_id']],true);
											return  Html::a('<span class="fa fa-trash"></span >&nbsp;Delete', $url,
												[ 'title' => Yii::t('app', 'View'), 'class'=>'btn btn-primary btn-xs', 'onclick'=>'return (confirm("Are you sure you want to delete this address?"))']
											) ;
										},
									]
								],
							],
						]); ?>
					</div>
				</div>

				<div class="clearfix"></div>

				<hr />

				<center class="submitt_buttons">
					<a class="btn btn-default" data-toggle="modal" data-target="#EventModal">
						<?php echo Yii::t('frontend','Add new event') ?>
					</a>
				</center>
<!--				<div class="thing_inner_items border_none --><?php //if($slug=='thingsilike'){echo 'active';}?><!--">-->
<!--					<a href="#" data-toggle="modal" data-target="#EventModal" title="">&nbsp;</a>-->
<!--				</div>-->
			</div>
	</div>
</section>
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
					jQuery("#oner").load("<?= Url::toRoute('product/event_slider'); ?>");
					jQuery('a#'+x).parent('li').remove();
					jQuery("#loader1").hide();
					jQuery("#loader1").hide();
					jQuery('ul#user_event_list').html(data);
					jQuery('#login_success').modal('show');
					jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success">Event remove from your event list</span>');
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


	$('#myTabs a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	})
</script>

<?php
$this->registerCss("
.search_data{padding: 10px;}
.loader1{display:none;text-align:center;margin-bottom: 10px;}
.item-img{width:210px; height:208px;}
.eventErrorMsg{color:red;margin-bottom: 10px;}
.event_loader{display:none;text-align:center;margin-bottom: 10px;}
.msg-success{margin-top: 5px; width: 320px; float: left; text-align: left;}

table{    font-size: 12px;}
.header-updated{padding-bottom:0; margin-bottom: 0;}
.body-updated{background: white; margin-top: 0;}
#inner_pages_sections .container{background:#fff; margin-top:12px;}
.border-left{border-left: 1px solid #e2e2e2;}
");


?>


