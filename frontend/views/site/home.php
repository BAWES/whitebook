<?php   
$this->title = 'Home | Whitebook';
use frontend\models\Website;
$model=new Website(); 
?>
<link href="<?php echo Yii::$app->params['CSS_PATH'];?>bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->params['CSS_PATH'];?>/layerslider.css" rel="stylesheet">
<!-- content main start -->  
	<!-- banner section start -->
		<?php if(count($banner)>0) {  ?>
	        <section id="banner_sections">     
            <div class="banner_slider_content">
				 <div class="carousel slide" id="myCarousel">
				    <ol class="carousel-indicators">
                        <li class="active" data-slide-to="0" data-target="#myCarousel"></li>
                        <li data-slide-to="1" data-target="#myCarousel" class=""></li>
                        <li data-slide-to="2" data-target="#myCarousel" class=""></li>
                    </ol>
                     <div class="carousel-inner">
			<?php $i=1; 
			foreach($banner as $b) { ?>
                        <div class="item <?php if($i==1){ ?>active<?php } ?>">
				<?php if($b['slide_video_url']!='') { ?>
				<img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>/banner.png" class="ls-bg" style="top: 80px;" alt="Slide background"/>
				<?php } else { ?>
				<img src="<?php echo Yii::$app->params['BASE_URL'];?>/backend/web/uploads/banner_images/banner_<?php echo $b['slide_id'].'.png';?>" class="ls-bg" style="top: 80px;"  alt="<?php echo $b['slide_title'];?>" width="1322" height="522"/>
				<?php } ?>
                        </div>                    
                	<?php $i++; } ?>
                	</div>
                    <a data-slide="prev" href="#myCarousel" class="left carousel-control">‹</a>
                    <a data-slide="next" href="#myCarousel" class="right carousel-control">›</a>
                </div>
            </div>
        </section>
        <?php } ?>

	<!-- banner section end -->
	
<!-- Content start -->

        <section id="content_section">
            <div class="container_plan">
                <div class="container_common">
                    <span class="first_events"><img src="<?php echo Yii::$app->params['BASE_URL'];?>/frontend/web/images/my_book.png" alt="My White Book"/></span>
                    <div class="creatfirst_events">
                        <p data-example-id="active-anchor-btns" class="bs-example">
                            <a role="button" class="btn btn-default" title="Create your first event" href="#">Create your first event</a>
                        </p>
                    </div>
                </div>
                <div class="plan_sections">
                    <ul>
                        <li>              
                            <div class="plan_list">
                                <img src="<?php echo Yii::$app->params['BASE_URL'];?>/frontend/web/images/plan.jpg" alt="images"/> 
                                <div class="inner_content_plan">
                                    <h1>Plan</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec gravida convallis metus,</p>
                                    <a role="button" class="btn btn-default" title="Discover" href="#">Discover</a>
                                </div>
                            </div> 
                        </li>
                        <li>
                            <div class="plan_list">
                                <img src="<?php echo Yii::$app->params['BASE_URL'];?>/frontend/web/images/shop.jpg" alt="images"/> 
                                <div class="inner_content_plan">
                                    <h1>SHOP</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec gravida convallis metus,</p>
                                    <a role="button" class="btn btn-default" title="Discover" href="#">Discover</a>
                                </div>
                            </div>
                        </li>
                        <li><div class="plan_list">
                                <img src="<?php echo Yii::$app->params['BASE_URL'];?>/frontend/web/images/plan.jpg" alt="images"/> 
                                <div class="inner_content_plan">
                                    <h1>Experience</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec gravida convallis metus,</p>
                                    <a role="button" class="btn btn-default" title="Discover" href="#">Discover</a>
                                </div>
                            </div>
                        </li>
                    </ul>  
                </div>
                <div class="feature_product_title">
                    <h2>Featured Products</h2>
                </div>
                <div class="feature_product_slider">
                    <div class="most_popular_slider">
					
					<div class="slider_new_up">
					
                        <div class="flexslider2">
                            <ul class="slides">
								<!-- Feature product from  DB Start-->
								<?php //print_r ($featured_product);die; ?>
								
					<?php	foreach($featured_product as $f) { 
						?>
					<li class="col-md-4">
						<div class="featured_img">
							<div class="col-md-12 hover_tooltip">
								<div class="inner_tooltip">
									<div class="box_item1">
										<h3><?php echo $f['vendor_name'];?></h3>
										<h2><?php echo $f['item_name'];?></h2>
										<div class="text-center"><span class="borderslid"></span></div>
										<label class="prize_cont"><?php echo number_format($f['item_price_per_unit'],2).Yii::$app->params['CURRENCY_CODE'];?></label>
										<div class="favourite">
											<div class="favourite_left">
												<?php if(Yii::$app->params['CUSTOMER_ID']=='') { ?>
												<a href="" data-toggle="modal" data-target="#loginmodal" title="Add to wishlist"> <span class="flaticon-favorite21"></span></a>
												<?php } else { 
													$check_user_fav=$model->check_user_fav($f['item_id']);
													if(count($check_user_fav)==0 || $check_user_fav[0]['wish_status']==0)
													{
													?>
													<a href="javascript:void(0);" onclick="wishlist(<?php echo $f['item_id'];?>,<?php echo Yii::$app->params['CUSTOMER_ID'];?>,this)" title="Add to wishlist"><span class="flaticon-favorite21" id="add_wishlist_<?php echo $f['item_id'];?>"></span></a>
													<?php 
													} 
													else 
													{ ?>
													<a href="<?php echo Yii::$app->params['BASE_URL'];?>/events.html" title="Wishlist"><span style="color:#ebc000;" class="flaticon-favorite21"></span></a>
												<?php } } ?>
											</div>
											<div class="favourite_right">
												<span class="add_but">
													<?php if(Yii::$app->params['CUSTOMER_ID']=='') { ?>
													<a href="" data-toggle="modal" data-target="#loginmodal"  title="Add Event">+</a>
													<?php } else { ?>
													<a href="" onclick="show_add_event_form();" data-toggle="modal" data-target="#addevent<?php echo $f['item_id'];?>"  title="Add Event">+</a>
													<?php } ?>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<img src="<?php echo Yii::$app->params['IMAGE_PATH'];?>/service1.jpg" alt=""/>
						</div>
					</li>
					<?php } ?>
								<!-- Feature product from  DB End-->
								
                        </div>
						</div>
                    </div>
                </div>
                <div class="add_banner">
                    <img src="<?php echo Yii::$app->params['BASE_URL'];?>/frontend/web/images/explore_banner.jpg" alt="banner"/>
                </div>
            </div> 
        </section>	


<!-- content main end  -->  


 <!-- Modal video start -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content  modal_member_login signup_poupu row">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-8">
						<iframe id="banner_iframe_src" width="583" height="315" src="" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- video end -->


<?php if(count($featured_product)>0) { 
		foreach($featured_product as $f) {  ?>
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
									<h5><?php echo number_format($f['item_price_per_unit'],2).Yii::$app->params['CURRENCY_CODE'];?></h5>
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
<!-- end -->
<!-- Modal after logedin page -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
										<div class="bs-docs-example"><select class="selectpicker required" name="event_type" data-style="btn-primary" id="event_type" >
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
<!-- end -->


<script>
	jQuery("#layerslider").layerSlider({
		responsive: false,
		responsiveUnder: 1280,
		layersContainer: 1280,
		skin: 'noskin',
		hoverPrevNext: false,
		navButtons:true,
		skinsPath: '../layerslider/skins/'
	});
	
	 $(function () {
         $('.datetimepicker').datetimepicker({format: "DD-MM-YYYY"});
         $('.create_event_form').hide();
	});
	
	/*home featured_img js start*/
	$(window).load(function () {
		/* client say slider start*/
		$('.flexslider2').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: true,
			slideshow: true,
			itemWidth: 370,
			reverse: true,
			itemMargin: 150,
			pauseOnHover: true,
			slideshowSpeed: 10000,
			move: 1,
			asNavFor: '#slider',
			minItems: 3,
			maxItems:20,
			startAt: 1,
			start: function (slider) {
				$('body').removeClass('loading');
			}
		});

	});
	</script>