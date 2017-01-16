<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use admin\models\VendorItem;
use common\models\VendorItemThemes;
use common\models\VendorItemPricing;
use common\models\FeatureGroupItem;
use common\models\Image;
use common\models\VendorItemQuestion;
use common\models\VendorItemQuestionGuide;
use common\components\CFormatter;

$arr_categories = [];

foreach($categories as $key => $value) 
{ 
    $arr_categories[] = $value->category->category_title;
} 

$this->title = 'Vendor Draft Item Details';

$this->params['breadcrumbs'][] = ['label' => 'Draft Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->item_name;

?>
<div class="vendoritem-view">


<div class="col-md-12 col-sm-12 col-xs-12">

  <?= Html::a('Back', ['index'], ['class' => 'btn btn-default']) ?>
  <?= Html::a('Approve', ['approve', 'id' => $model->draft_item_id], ['class' => 'btn btn-success']) ?>

  <button type="button" class="btn btn-primary btn-reject" type="button" data-id="<?= $model->draft_item_id ?>">
    Reject
  </button>

<br />
<br />

<div class="alert alert-info">
  Fields mark with (*) are changed. 
  <button class="close" data-dismiss="alert">&close;</button>
</div>

<?php if($is_price_table_changed) { ?>
<div class="alert alert-info">
  Price table changed.
  <button class="close" data-dismiss="alert">&close;</button>
</div>
<?php } ?>

<?php if($is_images_changed) { ?>
<div class="alert alert-info">
  Images changed.
  <button class="close" data-dismiss="alert">&close;</button>
</div>
<?php } ?>

<?php if($is_categories_changed) { ?>
<div class="alert alert-info">
  Categories changed.
  <button class="close" data-dismiss="alert">&close;</button>
</div>
<?php } ?>

<!-- Begin Twitter Tabs-->
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#1" data-toggle="tab">Vendor Info </a></li>
        <li><a href="#2" data-toggle="tab">Priority Log</a></li>
        <li><a href="#3" data-toggle="tab">Gallery</a></li>
        <?php if($price_table) { ?>
        <li><a href="#4" data-toggle="tab">Price Table</a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">

<!-- Begin First Tab -->
        <div class="tab-pane" id="1" ><div class="admin" style="text-align: center;padding:0px 0px 25px 0px;">
            <?php if(isset($model->vendor_logo_path)) {
                echo Html::img(Yii::getAlias('@s3/vendor_logo/').$model->vendor_logo_path, ['class'=>'','width'=>'125px','height'=>'125px','alt'=>'Logo']);
            } ?>
            </div>
            <div class="form-group">
                   <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //'item_id',
                        [
                            'label' => $vendor_item->type_id != $model->type_id? 'Vendor Type *' : 'Vendor Type ',
                            'value' => VendorItem::getItemType($model->type_id),
                        ],
                        [
                            'label' => $vendor_item->vendor_id != $model->vendor_id? 'Vendor *' : 'Vendor ',
                            'value' => VendorItem::getVendorName($model->vendor_id),
                        ],
                        [
                            'label' => 'Categories',
                            'format' => 'raw',
                            'value' => implode('<br />', $arr_categories)
                        ],
                        [
                            'label' => $vendor_item->item_name != $model->item_name ? 'ITEM NAME *' : 'ITEM NAME ',
                            'value' => $model->item_name
                        ],
                        [
                            'label' =>  $vendor_item->item_name_ar != $model->item_name_ar ? 'ITEM NAME - ARABIC *' : 'ITEM NAME - ARABIC',
                            'value' => $model->item_name_ar
                        ],
                        [
                            'label' => $vendor_item->item_description != $model->item_description ? 'Item description *' : 'Item description',
                            'format' => 'raw',
                            'value' => strip_tags($model->item_description),
                        ],
                        [
                            'label' => $vendor_item->item_description_ar != $model->item_description_ar ? 'Item description - Arabic *' : 'Item description - Arabic',
                            'format' => 'raw',
                            'value' => strip_tags($model->item_description_ar),
                        ],
                        [
                            'label' => $vendor_item->item_additional_info != $model->item_additional_info ? 'Item additional info *' : 'Item additional info',
                            'format'=>'raw',
                            'value' =>strip_tags($model->item_additional_info),
                        ],
                        [
                            'label' => $vendor_item->item_additional_info_ar != $model->item_additional_info_ar ? 'Item additional info - Arabic *' : 'Item additional info - Arabic',
                            'format' => 'raw',
                            'value' => strip_tags($model->item_additional_info_ar),
                        ],
                        [
                            'label' => $vendor_item->item_amount_in_stock != $model->item_amount_in_stock ? 'ITEM AMOUNT IN STOCK *' : 'ITEM AMOUNT IN STOCK',
                            'value' => $model->item_amount_in_stock,
                        ],
                        [
                            'label' => $vendor_item->item_default_capacity != $model->item_default_capacity ? 'ITEM DEFAULT CAPACITY *' : 'ITEM DEFAULT CAPACITY',
                            'value' => $model->item_default_capacity,
                        ],
                        [
                            'label' => $vendor_item->item_customization_description != $model->item_customization_description ? 'Item customization description *' : 'Item customization description',
                            'format' => 'raw',
                            'value' => strip_tags($model->item_customization_description),
                        ],
                        [
                            'label' => $vendor_item->item_customization_description_ar != $model->item_customization_description_ar ? 'Item customization description - Arabic *' : 'Item customization description - Arabic',
                            'format' => 'raw',
                            'value' => strip_tags($model->item_customization_description_ar),
                        ],
                        [
                            'label' => $vendor_item->item_price_description != $model->item_price_description ? 'Item price description *' : 'Item price description',
                            'value' => strip_tags($model->item_price_description),
                        ],
                        [
                            'label' => $vendor_item->item_price_description_ar != $model->item_price_description_ar ? 'Item price description - Arabic*' : 'Item price description - Arabic',
                            'value' => strip_tags($model->item_price_description_ar),
                        ],
                        [
                            'label' => $vendor_item->item_for_sale != $model->item_for_sale ? 'ITEM FOR SALE *' : 'ITEM FOR SALE',
                            'value' => strip_tags($model->item_for_sale),
                        ],
                        [
                            'label' => $vendor_item->item_how_long_to_make != $model->item_how_long_to_make ? 'ITEM HOW LONG TO MAKE *' : 'ITEM HOW LONG TO MAKE',
                            'value' => strip_tags($model->item_how_long_to_make),
                        ],
                        [
                            'label' => $vendor_item->item_minimum_quantity_to_order != $model->item_minimum_quantity_to_order ? 'ITEM MINIMUM QUANTITY TO ORDER *' : 'ITEM MINIMUM QUANTITY TO ORDER',
                            'value' => strip_tags($model->item_minimum_quantity_to_order),
                        ],
                        [
                            'label' => $vendor_item->item_approved != $model->item_approved ? 'ITEM APPROVED *' : 'ITEM APPROVED',
                            'value' => strip_tags($model->item_approved),
                        ],
                        [
                            'label' => 'Themes',
                            'value' => $model->getThemeName(),
                        ],
                        [
                            'label' => 'Group',
                            'value' => FeatureGroupItem::groupList($model),
                        ],
                        [
                            'attribute' => 'created_datetime',
                            'format' => ['date', 'php:d/m/Y'],
                            'label' => 'created date',
                        ],
                        [
                            'label' => $vendor_item->item_price_per_unit != $model->item_price_per_unit ? 'ITEM PRICE PER UNIT *' : 'ITEM PRICE PER UNIT',
                            'value' => $model->item_price_per_unit
                        ]                        
                  ],
                ]) ?>

            </div>
        </div>

        <div class="tab-pane" id="2">
            <table class="table table-striped table-bordered detail-view">
                <tbody>
                    <tr><th>Priority Level</th><th>Start Date</th><th>End Date</th></tr>
                        <?php $model->item_id; foreach ($dataProvider1 as $log) { ?>
                            <tr><td><?php print_r($log['priority_level']);?></td><td><?php $sd=($log['priority_start_date'][0]);$sd=($log['priority_start_date']); echo date("d/m/Y", strtotime($sd)); ?></td><td><?php $sd=($log['priority_end_date']);if($sd=='0000-00-00 00:00:00'){echo 'not set';}else { echo date("d/m/Y", strtotime($sd));} ?></td></tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane" id="3">
            <ul class="row">
                <?php foreach ($imagedata as $key => $image) {
                    $alias = Yii::getAlias('@vendor_item_images_210/');
                    ?>
                    <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                        <?= Html::img($alias.$image->image_path, ['style'=>'width:140px;height:140px;', 'class'=>'img-responsive','id' => 'image-'.$key,'alt'=>'Gallery','data-img'=>Yii::getAlias('@web/uploads/vendor_images/').$image->image_path]); ?>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="tab-pane" id="4">
            <table class="table table-striped table-bordered detail-view">
                <thead>
                    <tr>
                        <th><?= Yii::t('frontend', 'From') ?></th>
                        <th><?= Yii::t('frontend', 'To') ?></th>
                        <th><?= Yii::t('frontend', 'Price per unit') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($price_table as $key => $value) { ?>
                    <tr>
                        <td>
                            <?= $value->range_from ?> 
                            <?= Yii::t('frontend', 'Unit') ?>
                        </td>
                        <td>
                            <?= $value->range_to ?> 
                            <?= Yii::t('frontend', 'Unit') ?>
                        </td>
                        <td>
                            <?= CFormatter::format($value->pricing_price_per_unit) ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

<!--End fourth Tab -->
</div>

<div class="modal fade view-modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="z-index: 99999;">
      <div class="modal-content">
        <div class="modal-body"></div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_reject" id="modal_reject" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reject item</h4>
      </div>
      <div class="modal-body">
        <form>
            <input type="hidden" name="draft_item_id" value="<?= $model->draft_item_id ?>" />
            <textarea class="form-control" name="reason" placeholder="Reason for rejection"></textarea>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-reject-submit">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php 

$this->registerJs("

  var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
  
  /* Begin when loading page first tab opened */
  $(function (){ 
        $('.nav-tabs li:first').addClass('active');
        $('.tab-content div:first').addClass('active');
  });

  function questionView(q_id,tis) { // single question view

    var check = $('.show_ques'+q_id).html();
    
    if (check=='') {
          
      var path = '".Url::to(['/vendor-item/viewrenderquestion'])."';

      $.ajax({
          type : 'POST',
          url :  path,
          data: { q_id: q_id ,_csrf : csrfToken}, //data to be send
          success: function( data ) {
            $('.show_ques'+q_id).html(data);
            $(tis).toggleClass('expanded');
            return false;
          }
      });

    } else {
      $('.show_ques'+q_id).toggle();
      $(tis).toggleClass('expanded');
    }
  }

", View::POS_HEAD);
	
$this->registerCss("
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
      .view-modal .modal-body {
          padding:5px !important;
      }
      .view-modal .modal-content {
          border-radius:0;
      }
      .view-modal .modal-dialog img {
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
      .view-modal .modal-dialog {
          max-width:500px;
          padding-top: 90px;
      }
      @media screen and (min-width: 768px){
          .view-modal .modal-dialog {
              width:500px;
              padding-top: 90px;
          }
      }
      @media screen and (max-width:1500px){
          #ads {
              display:none;
          }
      }
");
     
echo Html::hiddenInput('reject_url', Url::to(['vendor-draft-item/reject']), ['id' => 'reject_url']);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-modal-box/photo-gallery.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_draft_item.js?v=1.1", ['depends' => [\yii\web\JqueryAsset::className()]]);
