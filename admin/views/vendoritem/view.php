<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Vendoritem;
use common\models\Vendoritemthemes;
use common\models\Vendoritempricing;
use common\models\Featuregroupitem;


use common\models\Image;
use common\models\Vendoritemquestion;
use common\models\Vendoritemquestionguide;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritem */

$this->title = 'Vendor Item Details';
//$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Vendoritems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->item_name;

?>
<div class="vendoritem-view">
    <p>
		<?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
		<?= Html::a('Update', ['update', 'id' => $model->item_id], ['class' => 'btn btn-success']) ?>
    </p>


<div class="col-md-12 col-sm-12 col-xs-12">
<!-- Begin Twitter Tabs-->
<div class="tabbable">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#1" data-toggle="tab">Vendor Info </a>
    </li>
    <li>
      <a href="#2" data-toggle="tab">Priority Log</a>
    </li>   
    <?php if($model->type_id=='2'){ ?> 
    <li>
      <a href="#3" data-toggle="tab">Question Answer Details</a>
    </li>  <?php } ?> 
    <li>
      <a href="#4" data-toggle="tab">Gallery</a>
    </li>
  </ul>
  <div class="tab-content">
	  
<!-- Begin First Tab -->
<div class="tab-pane" id="1" ><div class="admin" style="text-align: center;padding:0px 0px 25px 0px;">
<?php if(isset($model->vendor_logo_path)) {
		echo Html::img(Yii::getAlias('@web/uploads/vendor_logo/').$model->vendor_logo_path, ['class'=>'','width'=>'125px','height'=>'125px','alt'=>'Logo']);
		} ?>
		</div>
<div class="form-group">
	   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'item_id',
            [
            'label'=>'Vendor Type',			
			'value'  => Vendoritem::getItemType($model->type_id),
			],
            [
            'label'=>'Vendor',			
			'value'  => Vendoritem::getVendorName($model->vendor_id),
			],
			[
            'label'=>'Parent Category Name',
			'value'  => Vendoritem::getCategoryName($model->category_id),
			],
			[
            'label'=>'Subparent Category Name',			
			'value'  => Vendoritem::getCategoryName($model->subcategory_id),
			],
			[
            'label'=>'Child Category Name',			
			'value'  => Vendoritem::getCategoryName($model->child_category),
			],
            'item_name',
            [
            'label'=>'Item customization description',	
            'format'=>'raw',			
			'value'  =>strip_tags($model->item_description),
			],
            [
            'label'=>'Item customization description',		
            'format'=>'raw',	
			'value'  =>strip_tags($model->item_additional_info),
			],
            'item_amount_in_stock',
            'item_default_capacity', 

			[
            'label'=>'Item customization description',		
            'format'=>'raw',		
			'value'  =>strip_tags($model->item_customization_description),
			],
			[
            'label'=>'Item price description',			
			'value'  =>strip_tags($model->item_price_description),
			],
            'item_for_sale',
            'item_how_long_to_make',
            'item_minimum_quantity_to_order',          
            'item_approved',			
			
            [
            'label'=>'Themes',			
			'value'  => Vendoritemthemes::themedetails($model->item_id),
			],
			
			[
            'label'=>'Group',			
			'value'  => Featuregroupitem::groupdetails($model->item_id),
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
      ],
    ]) ?>    
      <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
              'item_price_per_unit',           
        ],
      ]);    
      Vendoritempricing::loadviewprice($model->item_id, $model->type_id, $model->item_price_per_unit);
      ?>
</div> 
</div>

<!--End First Tab -->


<div class="tab-pane" id="2">  

<table class="table table-striped table-bordered detail-view">
	<tbody>
		<tr>
			<th>Priority Level</th><th>Start Date</th><th>End Date</th>
			</tr>
			<?php $model->item_id; foreach ($dataProvider1 as $log) {?>
			<tr>
			<td><?php print_r($log['priority_level']);?></td><td><?php $sd=($log['priority_start_date'][0]);$sd=($log['priority_start_date']); echo date("d/m/Y", strtotime($sd)); ?></td><td><?php $sd=($log['priority_end_date']);if($sd=='0000-00-00 00:00:00'){echo 'not set';}else { echo date("d/m/Y", strtotime($sd));} ?></td>
			</tr>
			<?php }?>
	</tbody>
</table>

</div> 

<?php if($model_question=='2') {?>
<!--End Second Tab -->
<div class="tab-pane" id="3">  
<?php 
$t=0;
foreach($model_question as $question_records)
	 {?>
		 <div class="form-group superbox" id="delete_<?= $t;?>"> 

	 
	 	<div class="form-group superbox-s" id="delete_<?= $t;?>"> 

		<li class="parent_question"><?= ucfirst($question_records['question_text']); ?><span  class="plus"><a href="#" onclick="questionView('<?= $question_records['question_id']; ?>',this)"></a></span><div class="show_ques<?= $question_records['question_id']; ?>"></div></li>
	
	</div>
	</div>
	<?php $t++;}	?>		
</div> 
<!--End third Tab -->
<?php } ?>

<div class="tab-pane" id="4" > 
<ul class="row">
  			<?php foreach ($imagedata as $image) { ?>
            <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
               <?= Html::img(Yii::getAlias('@web/uploads/vendor_images/').$image->image_path, ['class'=>'img-responsive','width'=>'125px','height'=>'125px','id'=>$image->image_id,'alt'=>'Gallery','data-img'=>Yii::getAlias('@web/uploads/vendor_images/').$image->image_path]);?> 
            </li>
             <?php } ?>     
</ul>
</div>

<!--End fourth Tab -->
</div>

<script>
	
	var csrfToken = $('meta[name="csrf-token"]').attr("content");
 /* Begin when loading page first tab opened */
 $(function (){
        $('.nav-tabs li:first').addClass("active");
        $(".tab-content div:first").addClass("active");
	});
        /* End when loading page first tab opened */


// single question view
function questionView(q_id,tis){
	
	var check = $('.show_ques'+q_id).html();
	if(check==''){
	var path = "<?php echo Url::to(['/admin/vendoritem/viewrenderquestion']); ?> ";	
	$.ajax({
		type : 'POST',
		url :  path,
		data: { q_id: q_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {        
        $('.show_ques'+q_id).html(data);
        $(tis).toggleClass("expanded");
        return false;    	
        }
	})
	}else{
			$('.show_ques'+q_id).toggle();
			$(tis).toggleClass("expanded");
	}

}
</script>
 <style>
      ul {         
          padding:0 0 0 0;
          margin:0 0 0 0;
      }
      ul li {     
          list-style:none;
          margin-bottom:25px;           
      }
      ul li img {
          cursor: pointer;
      }
      .modal-body {
          padding:5px !important;
      }
      .modal-content {
          border-radius:0;
      }
      .modal-dialog img {
          text-align:center;
          margin:0 auto;
      }
    .controls{          
        width:50px;
        display:block;
        font-size:11px;
        padding-top:8px;
        font-weight:bold;          
    }
    .next {
        float:right;
        text-align:right;
    }
      /*override modal for demo only*/
      .modal-dialog {
          max-width:500px;
          padding-top: 90px;
      }
      @media screen and (min-width: 768px){
          .modal-dialog {
              width:500px;
              padding-top: 90px;
          }          
      }
      @media screen and (max-width:1500px){
          #ads {
              display:none;
          }
      }
  </style>

 
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-modal-box/photo-gallery.js"></script>
    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="z-index: 99999;">
        <div class="modal-content">         
          <div class="modal-body">                
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
