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

					<?php  /*

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

					*/ ?>

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

			<?= $this->render('@frontend/views/common/items', [
                'items' => $provider, 
                'customer_events_list' => $customer_events
            ]); ?>

		</div><!-- END .package_items -->

		<br />
		<br />

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
