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
use common\models\VendorItemMenuItem;

$arr_categories = [];

foreach($categories as $key => $value) { 
    $arr_categories[] = $value->category->category_title;
} 

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
        <li class="active"><a href="#1" data-toggle="tab">Vendor Info </a></li>
        <li><a href="#2" data-toggle="tab">Priority Log</a></li>
        <li><a href="#3" data-toggle="tab">Options</a></li>
        <li><a href="#4" data-toggle="tab">Addons</a></li>
        <li><a href="#5" data-toggle="tab">Gallery</a></li>
    </ul>
    <div class="tab-content">

        <!-- Begin First Tab -->
        <div class="tab-pane" id="1"><div class="admin" style="text-align: center;padding:0px 0px 25px 0px;">
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
                            'label'=>'Vendor Type',
                            'value'  => VendorItem::getItemType($model->type_id),
                        ],
                        [
                            'label'=>'Vendor',
                            'value'  => VendorItem::getVendorName($model->vendor_id),
                        ],
                        [
                            'label'=>'Categories',
                            'format'=>'raw',
                            'value'  => implode('<br />', $arr_categories)
                        ],
                        'item_name',
                        'item_name_ar',
                        [
                            'label'=>'Item description',
                            'format'=>'raw',
                            'value'  =>strip_tags($model->item_description),
                        ],
                        [
                            'label'=>'Item description - Arabic',
                            'format'=>'raw',
                            'value'  =>strip_tags($model->item_description_ar),
                        ],
                        [
                            'label'=>'Item additional info',
                            'format'=>'raw',
                            'value'  =>strip_tags($model->item_additional_info),
                        ],
                        [
                            'label'=>'Item additional info - Arabic',
                            'format'=>'raw',
                            'value'  =>strip_tags($model->item_additional_info_ar),
                        ],
                        'item_default_capacity',
                        [
                            'label'=>'Item customization description',
                            'format'=>'raw',
                            'value'  =>strip_tags($model->item_customization_description),
                        ],
                        [
                            'label'=>'Item customization description - Arabic',
                            'format'=>'raw',
                            'value'  =>strip_tags($model->item_customization_description_ar),
                        ],
                        [
                            'label'=>'Item price description',
                            'value'  =>strip_tags($model->item_price_description),
                        ],
                        [
                            'label' =>'Item price description - Arabic',
                            'value' => strip_tags($model->item_price_description_ar),
                        ],
                        'item_for_sale',
                        
                        'quantity_label',
                        'set_up_time',
                        'set_up_time_ar',
                        'max_time',
                        'max_time_ar',
                        'requirements',
                        'requirements_ar',
                        'min_order_amount',

                        [
                            'label'=>'ALLOW SPECIAL REQUEST?',
                            'format'=>'raw',
                            'value'  => $model->allow_special_request ? 'Yes' : 'No',
                        ],
                        [
                            'label'=>'HAVE FEMALE SERVICE?',
                            'format'=>'raw',
                            'value'  => $model->have_female_service ? 'Yes' : 'No',
                        ],

                        'item_how_long_to_make',
                        'item_minimum_quantity_to_order',
                        'item_approved',
                        [
                            'label'=>'Themes',
                            'value'  => $model->getThemeName(),
                        ],
                        [
                            'label'=>'Group',
                            'value'  => FeatureGroupItem::groupList($model),
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
                  VendorItemPricing::loadviewprice($model->item_id, $model->type_id, $model->item_price_per_unit);
                  ?>
            </div>
        </div>

        <!--End First Tab -->
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
          <ul id="item_addon_menu_list">
          <?php foreach ($arr_menu as $key => $value) { ?>
            <li>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th colspan="5" class="heading">
                      Menu
                    </th>
                  </tr>
                  <tr>
                    <th>Name</th>
                    <th>Name - Ar</th>
                    <th>Min Qty</th>
                    <th>Max Qty</th>
                    <th>Qty Type</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="required">
                      <?= $value->menu_name ?>
                    </td>
                    <td class="required">
                      <?= $value->menu_name_ar ?>
                    </td>
                    <td>
                      <?= $value->min_quantity ?>
                    </td>
                    <td>
                      <?= $value->max_quantity ?>
                    </td>
                    <td>
                      <?= $value->quantity_type ?>
                    </td>
                </tr>
                </tbody>
              </table>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th colspan="5" class="heading">Menu Items</th>
                  </tr>
                  <tr>
                    <th>Name</th>
                    <th>Name - Ar</th>
                    <th>Hint</th>
                    <th>Hint - Ar</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                  $arr_menu_item = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

                  foreach ($arr_menu_item as $key => $menu_item) { ?>
                  <tr>
                    <td class="required">
                      <?= $menu_item->menu_item_name ?>
                    </td>
                    <td class="required">
                      <?= $menu_item->menu_item_name_ar ?>
                    </td>
                    <td>
                      <?= $menu_item->hint ?>
                    </td>
                    <td>
                      <?= $menu_item->hint_ar ?>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </li>
          <?php } ?>
          </ul>
        </div>

        <div class="tab-pane" id="4">

          <ul id="item_addon_menu_list">

          <?php foreach ($arr_addon_menu as $key => $value) { ?>
          <li>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="2" class="heading">
                    Addon Menu
                  </th>
                </tr>
                <tr>
                  <th>Name</th>
                  <th>Name - Ar</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?= $value->menu_name ?>
                  </td>
                  <td>
                    <?= $value->menu_name_ar ?>
                  </td>
                </tr>
              </tbody>
            </table>

            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="6" class="heading">Menu Items</th>
                </tr>
                <tr>
                  <th>Name</th>
                  <th>Name - Ar</th>
                  <th>Price</th>
                  <th>Hint</th>
                  <th>Hint - Ar</th>
                </tr>
              </thead>
              <tbody>
                <?php 

                $arr_menu_item = VendorItemMenuItem::findAll(['menu_id' => $value->menu_id]);

                foreach ($arr_menu_item as $key => $menu_item) { ?>
                <tr>
                  <td>
                    <?= $menu_item->menu_item_name ?>
                  </td>
                  <td>
                    <?= $menu_item->menu_item_name_ar ?>
                  </td>
                  <td>
                    <?= $menu_item->price ?>
                  </td>
                  <td>
                    <?= $menu_item->hint ?>
                  </td>
                  <td>
                    <?= $menu_item->hint_ar ?>
                  </td>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </li>
          <?php } ?>
          </ul>
        </div>

        <div class="tab-pane" id="5">
            <ul class="row">
                <?php foreach ($imagedata as $image) {
                    $alias = ($image->module_type == 'vendor_item') ? Yii::getAlias('@vendor_item_images_210/') : Yii::getAlias('@sales_guide_images/')
                    ?>
                    <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                        <?= Html::img($alias.$image->image_path, ['style'=>'width:140px;height:140px;', 'class'=>'img-responsive','id'=>$image->image_id,'alt'=>'Gallery','data-img'=>Yii::getAlias('@web/uploads/vendor_images/').$image->image_path]);?>
                    </li>
                <?php } ?>
            </ul>
        </div>

<!--End fourth Tab -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="z-index: 99999;">
      <div class="modal-content">
        <div class="modal-body"></div>
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
");
     
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/plugins/bootstrap-modal-box/photo-gallery.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
