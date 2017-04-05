<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Image;
use common\models\VendorItemToPackage;
use frontend\models\Website;
use common\components\CFormatter;

?>

<section id="inner_page_detials">
	<div class="container">
	    <div class="title_main">
	    	<?php if(Yii::$app->language == 'en') { ?>
	        	<h1><?= $package->package_name ?></h1>
	        <?php } else { ?>
	        	<h1><?= $package->package_name_ar ?></h1>
	        <?php } ?>
	    </div>

	    <div class="package_description">
		    <div class="row">
			    <div class="col-lg-4">
			    	<div class="thumbnail">
			    		<img src="<?= Url::to("@s3/".$package->package_background_image); ?>" alt="<?= $package->package_name ?>" />
			    	</div>
				</div>
				<div class="col-lg-8">

				    <div class="event_form_wrapper">
				    	<form class="form-inline">
						    <fieldset>
						    	<legend><?= Yii::t('frontend', 'Add to event') ?></legend>

						    	<?php if (!Yii::$app->user->isGuest) { ?>

						    	<div class="form-group">
							    	<select name="event_id" style="display: none;">
							    		<?php foreach ($customer_events as $event) { ?>
							    		<option value="<?= $event->event_id ?>">
							    			<?= $event->event_name ?>
							    		</option>
							    		<?php } ?>
							    	</select>
						    	</div>

						    	<div class="form-group">
							    	<button class="btn btn-default btn-add-to-event" type="button">
							    		<i class="fa fa-plus"></i> 
							    		<?= Yii::t('frontend', 'Add') ?>
							    	</button>
						    	</div>

						    	<div class="or">
						    	or 
						    	</div>

						    	<div class="form-group">
							    	<button class="btn btn-default" data-toggle="modal" data-target="#modal_event_from_package" type="button"><?= Yii::t('frontend', 'Create Event') ?>
							    	</button>				
						    	</div>	

						    	<?php } else { ?>

							    	<a href="javascript:" role="button" class="btn btn-default" data-toggle="modal" onclick="show_login_modal(-1);" data-target="#myModal" title="Create Your First Event"><?= Yii::t('frontend', 'Create Your First Event') ?></a>

							    <?php } ?>

						    </fieldset>
					    </form>
				    </div><!-- END .event_form_wrapper -->

				    <hr />

			    	<p>
					    <?php if(Yii::$app->language == 'en') { ?>
					    	<?= $package->package_description ?>
					    <?php } else { ?>
					    	<?= $package->package_description_ar ?>
					    <?php } ?>
				    </p>

					<?php if($package->package_avg_price) { ?>
					<p>
				    	<b><?= Yii::t('frontend', 'Average price') ?></b> : 
				    	<?= $package->package_avg_price ?>
				    </p>
				    <?php } ?>

				    <?php if($package->package_number_of_guests) { ?>
				    <p>
				    	<b><?= Yii::t('frontend', 'No of guests') ?></b> : 
				    	<?= $package->package_number_of_guests ?>
				    </p>
				    <?php } ?>

			    </div><!-- END .col-lg-8 -->
			</div><!-- END .row -->
		</div><!-- END .package_description -->

		<div class="package_items">

		<?php 

		foreach ($categories as $key => $category) {

			$items = VendorItemToPackage::find()
			    ->select(['{{%vendor}}.vendor_name', '{{%vendor}}.vendor_name_ar', '{{%vendor_item}}.item_id', 
			    	'{{%vendor_item}}.item_price_per_unit', '{{%vendor_item}}.item_name', 
			    	'{{%vendor_item}}.item_name_ar', '{{%vendor_item}}.slug', 
			    	'{{%vendor_item}}.item_id', '{{%vendor_item}}.item_how_long_to_make'
			    ])
			    ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%vendor_item_to_package}}.item_id')
			    ->leftJoin(
			        '{{%vendor_item_to_category}}', 
			        '{{%vendor_item_to_category}}.item_id = {{%vendor_item}}.item_id'
			    )
			    ->leftJoin(
			        '{{%category_path}}', 
			        '{{%category_path}}.category_id = {{%vendor_item_to_category}}.category_id'
			    )
			    ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
			    ->where([
			        '{{%vendor_item}}.item_status' => 'Active',
			        '{{%vendor_item}}.trash' => 'Default',
			        '{{%category_path}}.path_id' => $category->category_id,
			        '{{%vendor_item_to_package}}.package_id' => $package->package_id
			    ])
			    ->groupBy('{{%vendor_item_to_package}}.item_id')
			    ->asArray()
			    ->all();

			if(!$items)
			{
				continue;
			}

			?>
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="heading<?= $key ?>">
				    <h4 class="panel-title">
				        <a data-toggle="collapse" id="description_click" data-parent="#accordion" href="#collapse<?= $key ?>" aria-expanded="false" aria-controls="collapse<?= $key ?>" class="collapsed">

				        <?php if(Yii::$app->language == "en"){
				                echo $category->category_name.' - '.'<span data-cateogry-id="'.$category->category_id.'" id="item_count">' .count($items). '</span>';
				              }else{
				                echo $category->category_name_ar.' - '.'<span id="item_count">' .count($items). '</span>';
				              }
				        ?>

				        <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a>
				    </h4>
				</div>
				<div id="collapse<?= $key ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $key ?>" aria-expanded="false">
				<div class="panel-body">				
					<?php 
					foreach ($items as $key => $value) { 
						
						$item_url = Url::to(["browse/detail", 'slug' => $value['slug']]);

				        $image_data = Image::find()
				            ->where(['item_id' => $value['item_id']])
				            ->orderBy(['vendorimage_sort_order' => SORT_ASC])
				            ->one();

				        if($image_data) {
				            $image = Yii::getAlias("@s3/vendor_item_images_210/").$image_data->image_path;
				        } else {
				            $image = Url::to("@web/images/item-default.png");    
				        }
					?>
					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 min-height-301 pull-left">
		            <div class="events_items width-100-percent">
		                <div class="events_images text-center position-relative">
		                    <div class="hover_events">
		                        <div class="pluse_cont">
		                            <?php if(Yii::$app->user->isGuest) { ?>
		                                <a href="" role="button" class="" data-toggle="modal"  onclick="show_login_modal(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Event');?>">
											<i class="fa fa-plus" aria-hidden="true"></i>
										</a>
		                            <?php } else { ?>
		                                <a href="#" role="button" id="<?php echo $value['item_id'];?>" name="<?php echo $value['item_id'];?>" class="" data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id'];?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend','Add to Event');?>">
											<i class="fa fa-plus" aria-hidden="true"></i>
										</a>
		                            <?php } ?>
		                        </div>

		                        <?php if(Yii::$app->user->isGuest) { ?>
		                            <div class="faver_icons">
		                                <a href="" role="button" class="" data-toggle="modal" id="<?php echo $value['item_id']; ?>" onclick="show_login_modal_wishlist(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Things I Like');?>">
											<i class="fa fa-heart-o" aria-hidden="true"></i>
										</a>
		                            </div>
		                        <?php } else { ?>
		                            <div class="faver_icons <?=(in_array($value['item_id'], $wishlist_item_ids)) ? 'faverited_icons' : ''?>">
		                                <a href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>">
											<i class="fa fa-heart<?=(in_array($value['item_id'],$wishlist_item_ids)) ? '' : '-o'?>" aria-hidden="true"></i>
										</a>
		                            </div>
		                            <?php } ?>
		                        </div>
		                        <a href="<?= $item_url ?>" class="" >
		                            
		                            <?= Html::img($image,['class'=>'item-img']); ?>

		                            <?php if($value['item_how_long_to_make'] > 0) { ?>
                                    <div class="callout-container" style="bottom: 10px;">
                                        <span class="callout light">
                                            <?php 

                                            if($value['item_how_long_to_make'] % 24 == 0) 
                                            { 
                                                echo Yii::t('frontend', 'Notice: {count} days', [
                                                    'count' => $value['item_how_long_to_make']/24
                                                ]); 
                                            }
                                            else
                                            {
                                                echo Yii::t('frontend', 'Notice: {count} hours', [
                                                    'count' => $value['item_how_long_to_make']
                                                ]);
                                            } ?>
                                        </span>
                                    </div>
                                    <?php } ?>

	                                <i class="fa fa-circle" aria-hidden="true"></i>
	                                <span class="buy-text"><?=Yii::t('frontend','Buy');?></span>
	                                <!--                            <img class="sale_ribbon" src="--><?//= Url::to('@web/images/product_sale_ribbon.png') ?><!--" />-->
	                                
		                        </a>
		                    </div>
		                    <div class="events_descrip">
		                        <a href="<?= $item_url ?>"><?= \common\components\LangFormat::format( $value['vendor_name'], $value['vendor_name_ar']) ?>
		                            <h3><?=\common\components\LangFormat::format( $value['item_name'], $value['item_name_ar'])?></h3>
		                            <p><?= CFormatter::format($value['item_price_per_unit'])  ?></p>
		                        </a>
		                    </div>
		                </div>
		            </div>
					<?php } ?>
				</div><!-- END .panel-body -->
				</div><!-- END .panel-collapse -->
			</div><!-- END .panel -->
		<?php } ?>

		</div><!-- END .package_items -->
	</div><!-- END .container -->
</section>


<?php if(!Yii::$app->user->isGuest) { ?>
<!-- BEGIN Create event Modal Box -->
<div class="modal fade" id="modal_event_from_package" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="eventModal">
        <div class="modal-content  modal_member_login signup_poupu row">
            <div class="modal-header">
                <button type="button" class="close" id="boxclose" name="boxclose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend', 'Create New Event'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <div class="product_popup_signup_box">
                            <div class="product_popup_signup_log">
                                <form>
                                    <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
                                    <div class="form-group">
                                        <input type="text" name="event_name" class="form-control required" id="event_name" placeholder="<?php echo Yii::t('frontend', 'Enter Event Name'); ?>" title="<?php echo Yii::t('frontend', 'Enter Event Name'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="item_id" class="form-control required" id="item_id" value="0">
                                    </div>
                                    <div class="form-group top_calie_new">
                                        <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                                            <input type="text"  name="event_date" id="event_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>">
                                            <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                                        </div>
                                        <label for="event_date" class="error"></label>
                                    </div>
                                    <div class="form-group new_popup_common">
                                        <div class="bs-docs-example">
                                            <select class="selectpicker required trigger" name="event_type" style="btn-primary" id="event_type" >
                                            <option value="">
                                                <?php echo Yii::t('frontend', 'Select event type') ?>
                                            </option>
                                            <?php $event_type = Website::get_event_types();
                                            foreach ($event_type as $e) { ?>
                                            <option value="<?php echo $e['type_name']; ?>">
                                                <?php echo $e['type_name']; ?>

                                            </option>
                                            <?php } ?>
                                            </select>

                                            <div class="error" id="type_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="no_of_guests" class="form-control required" id="no_of_guests" placeholder="<?php echo Yii::t('frontend', 'Enter number of guests'); ?>" />
                                    </div>

                                    <div id="eventresult"></div>
                                    <div class="eventErrorMsg error"></div>
                                    <div class="event_loader"><img src="<?php echo Url::to('@web/images/ajax-loader.gif', true); ?>" title="Loader"></div>
                                    <div class="buttons">
                                        <div class="creat_evn_sig">
                                            <button type="button" id="create_event_button" name="create_event_button" class="btn btn-default" title="<?php echo Yii::t('frontend', 'Create Event'); ?>"><?php echo Yii::t('frontend', 'Create Event'); ?></button>
                                        </div>
                                        <div class="cancel_sig">
                                            <input class="btn btn-default" data-dismiss="modal"  id="cancel_button" name="cancel_button" type="button" value="<?php echo Yii::t('frontend', 'Cancel'); ?>" title="<?php echo Yii::t('frontend', 'Cancel'); ?>">
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
<?php } ?>
<!-- END Create event Modal Box -->


<?php

echo Html::hiddenInput('package_id', $package->package_id, ['id' => 'package_id']); 
echo Html::hiddenInput('add_to_event_url', Url::to(["packages/add-to-event"]), ['id' => 'add_to_event_url']); 
echo Html::hiddenInput('package_event_url', Url::to(["packages/add-event"]), ['id' => 'package_event_url']); 
