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
			<h1><?php echo Yii::t('frontend','Things I Like'); ?></h1>
		</div>
		<div class="account_setings_sections">
			<?=$this->render('/users/_sidebar_menu');?>
			<div class="col-md-9 border-left">
				<div class="accont_informations">
					<?= \yii\grid\GridView::widget([
						'dataProvider' => $provider,
						'summary' => '',
						'columns' => [
							[
								'attribute'=>'image_path',
								'header'=>'image',
								'value' => function ($model) {
									return Html::a(Html::img(Yii::getAlias("@vendor_item_images_210/").$model['image_path'],['class'=>'table-item-img']), ['/browse/detail/','slug'=>$model['slug']]);
								},
								'format' =>'raw'
							],
							'item_name',
							'vendor_name',
							'item_for_sale',
							[
								'class' => 'yii\grid\ActionColumn',
								'header'=>'Action',
								'contentOptions' => ['class' => 'text-center'],
								'template' => '{view} {delete}',
								'buttons' => [
									'view' => function ($url, $model) {
										$url = Url::to(['/browse/detail/','slug'=>$model['slug']],true);
										return  Html::a('<span class="fa fa-search"></span> &nbsp;View', $url,
											[ 'title' => Yii::t('app', 'View'), 'class'=>'btn-view-thing-i-like btn btn-primary btn-xs', ]) ;
									},
									'delete' => function ($url, $model) {
										$url = Url::to(['things-i-like/delete','id'=>$model['item_id']],true);
										return  Html::a('<span class="fa fa-trash"></span >&nbsp;Delete', $url,
											[ 'title' => Yii::t('app', 'View'), 'class'=>'btn-delete-thing-i-like btn btn-primary btn-xs', 'onclick'=>'return (confirm("Are you sure you want to remove this item from your wishlist?"))']
										) ;
									},
								]
							],
						],
					]); ?>
				</div>
			</div>
		</div>
	</div>
</section>
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