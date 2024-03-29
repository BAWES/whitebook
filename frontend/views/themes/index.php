<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\view;
use common\components\LangFormat;
//$this->title = ''
?>

<!-- coniner start -->
<section id="inner_pages_white_back">

<div id="event_slider_wrapper">
	<div class="container paddng0">
		<?php $this->render('/product/events_slider.php'); ?>
	</div>
</div>

<div class="container paddng0">

	<div class="directory_listing">
		<div class="title_main">
			<h1><?= Yii::t("frontend", "Themes") ?></h1>
		</div>
		<div id="mobile_respon">
			<div class="mobile-view col-xs-12 padding0 directory-responsive">
				<?php $fl = $first_letter;
				?>
				<div class="tabContainer">
					<ul id="demoOne" class="demo">
						<?php
						foreach($fl as $f) {
							?>
							<li><h2><?php echo $f;?></h2></li>
							<?php
							foreach($directory as $d) {
								$theme_name = LangFormat::format($d['theme_name'],$d['theme_name_ar']);
								$ltr = LangFormat::format(strtoupper(mb_substr($d['theme_name'], 0, 1, 'utf8')),strtoupper(mb_substr($d['theme_name_ar'], 0, 1, 'utf8')));
								if($ltr === $f) { ?>
									<li><?= Html::a(strtoupper($theme_name),['detail','slug'=>'all','themes'=>$d['slug']]) ?></li>
								<?php }
							}	?>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>

		<div id="filter">
			<?php $total=count($directory);

			if($total>1){
				$first=$total/3;
				$second=$first+$first;
				$third=$second+$first;
				$k=$first_letter;
				$l=$first_letter;?>

				<!-- first section start here-->
				<div class="resposive-clearfix">
					<div class="col-md-4 resposive-clearfix pull-left">
					<?php 

					$i=0;

					foreach($first_letter as $f) {
						if ($i < $first) {
							?>
						<div class="theme_list">
							<h2><?php echo $f;?></h2>
							<ul>
							<?php 

							foreach($directory as $d) {
							$theme_name = LangFormat::format($d['theme_name'],$d['theme_name_ar']);
							$first_letter = LangFormat::format(strtoupper(mb_substr($d['theme_name'], 0, 1, 'utf8')),strtoupper(mb_substr($d['theme_name_ar'], 0, 1, 'utf8')));

							if($first_letter == $f)
								{if($i<$first){ ?>
								<li><?= Html::a(strtoupper($theme_name),['detail','slug'=>'all','themes'=>$d['slug']]) ?></li>
								<?php }  } }?>
							</ul>
						</div><!-- END .theme_list -->
						<?php }$i++; }?>
					</div><!-- END .resposive-clearfix -->
					<!-- first section end here-->

					<!-- second section start here-->
					<div class="col-md-4 pull-left">
					<?php 

					$i=0;
					foreach($k as $f) {  ?>
					<?php if(($i>=$first)&&($i<$second)){?>
						<div class="theme_list">
							<h2><?php echo $f;?></h2>
							<ul>
							<?php
							foreach($directory as $d) {

							$theme_name = LangFormat::format($d['theme_name'],$d['theme_name_ar']);
							$first_letter = LangFormat::format(strtoupper(mb_substr($d['theme_name'], 0, 1, 'utf8')),strtoupper(mb_substr($d['theme_name_ar'], 0, 1, 'utf8')));

							if($first_letter == $f) { ?>
								<li><?= Html::a(strtoupper($theme_name),['detail','slug'=>'all','themes'=>$d['slug']]) ?></li>
								<?php  

							} //if

							} //foreach ?>

							</ul>
						</div>
						<?php }$i++;}?>
					</div>
					<!-- second section end here-->

					<!-- Third section start here-->
					<div class="col-md-4 paddingright0 pull-left">

					<?php 

					$i=0;

					foreach($l as $f) {  
							
						if(($i>=$second)&&($i<$third)){ ?>
						
						<div class="theme_list">
							<h2><?php echo $f;?></h2>
							<ul>
							<?php
							
							foreach($directory as $d) {


							$theme_name = LangFormat::format($d['theme_name'],$d['theme_name_ar']);
							$first_letter = LangFormat::format(strtoupper(mb_substr($d['theme_name'], 0, 1, 'utf8')),strtoupper(mb_substr($d['theme_name_ar'], 0, 1, 'utf8')));

							if($first_letter == $f) { ?>
								<li><?= Html::a(strtoupper($theme_name),['detail','slug'=>'all','themes'=>$d['slug']]) ?></li>
								<?php  
							}//if

							}//foreach ?>

							</ul>
						</div>

						<?php }

						$i++;

					}//foreach ?>
					</div>
					<!-- Third section end here-->
			<?php 

			}//if total > 0 ?>
		</div><!-- END #filter -->
	</div><!-- END .directory_listing -->

</div><!-- END .container -->

</section>

<?php 

$this->registerCssFile('@web/css/listnav.css');
$this->registerJsFile('@web/js/jquery-listnav.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	
	jQuery('#demoOne').listnav();

	jQuery('.demo a').click(function(e) {
		e.preventDefault();
	});

	jQuery('#filter_category').change(function(){

		var x= jQuery('#filter_category').val();
		var ajaxdata= jQuery('#ajaxdata').val();

		var path =  '". Url::to('site/searchdirectory')."';
		
		jQuery.ajax({
			type:'POST',
			url:path,
			data:{ slug:x, ajaxdata:ajaxdata },
			success:function(data){
				if(ajaxdata=='1') {
					jQuery('#mobile_respon').html(data);
				} else {
					jQuery('#filter').html(data);
				}
			}
		}).done(function() {

			if(ajaxdata=='1'){
				jQuery('#demoOne').listnav();

				jQuery('.demo a').click(function(e) {
					e.preventDefault();
				});
			}

		});
	});

	jQuery('#demoOne a').click(function(){
		var directive_link=(jQuery(this).attr('href'));
		window.location.href=directive_link;
	});

	if (jQuery(window).width() < 991) {
		jQuery('#ajaxdata').val('1');
	}else{
		jQuery('#ajaxdata').val('0');
	}

", View::POS_READY);

	