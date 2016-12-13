<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\VendorItemToPackage;
use common\components\CFormatter;

?>

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
		        '{{%image}}.image_path','{{%vendor_item}}.item_price_per_unit',
		        '{{%vendor_item}}.item_name', '{{%vendor_item}}.item_name_ar', '{{%vendor_item}}.item_for_sale', '{{%vendor_item}}.slug', '{{%vendor_item}}.item_id'
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
		    ->leftJoin('{{%image}}', '{{%image}}.item_id = {{%vendor_item}}.item_id')
		    ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
		    ->where([
		        '{{%vendor_item}}.item_status' => 'Active',
		        '{{%vendor_item}}.trash' => 'Default',
		        '{{%category_path}}.path_id' => $category->category_id,
		        '{{%vendor_item_to_package}}.package_id' => $package->package_id
		    ])
		    ->groupBy('{{%vendor_item}}.item_id')
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
				?>
				<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 min-height-301 pull-left">
	            <div class="events_items width-100-percent">
	                <div class="events_images text-center position-relative">
	                    <div class="hover_events">
	                        <div class="pluse_cont">
	                            <?php if(Yii::$app->user->isGuest) { ?>
	                                <a href="" role="button" class="" data-toggle="modal"  onclick="show_login_modal(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
	                            <?php } else { ?>
	                                <a href="#" role="button" id="<?php echo $value['item_id'];?>" name="<?php echo $value['item_id'];?>" class="" data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id'];?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
	                            <?php } ?>
	                        </div>

	                        <?php if(Yii::$app->user->isGuest) { ?>
	                            <div class="faver_icons">
	                                <a href="" role="button" class="" data-toggle="modal" id="<?php echo $value['item_id']; ?>" onclick="show_login_modal_wishlist(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
	                            </div>
	                        <?php } else { ?>
	                            <div class="faver_icons <?=(in_array($value['item_id'], $wishlist_item_ids)) ? 'faverited_icons' : ''?>">
	                                <a href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
	                            </div>
	                            <?php } ?>
	                        </div>
	                        <a href="<?= $item_url ?>" class="" >
	                            <?php
	                            
	                            $path = (isset($value['image_path'])) ? Yii::getAlias("@s3/vendor_item_images_210/").$value['image_path'] : Url::to("@web/images/item-default.png");
	                                
	                            echo Html::img($path,['class'=>'item-img']);

	                            ?>
	                            <?php if($value['item_for_sale'] == 'Yes') { ?>
	                                <i class="fa fa-circle" aria-hidden="true"></i>
	                                <span class="buy-text"><?=Yii::t('frontend','Buy');?></span>
	                                <!--                            <img class="sale_ribbon" src="--><?//= Url::to('@web/images/product_sale_ribbon.png') ?><!--" />-->
	                            <?php } ?>
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
