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
use common\models\VendorItemMenu;
use common\models\VendorItemMenuItem;
use common\models\VendorDraftItemMenuItem;
use common\components\CFormatter;

$arr_categories = [];

foreach($categories as $key => $value) 
{ 
    $arr_categories[] = $value->category->category_title;
} 

$this->title = 'Item Approvals';

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
        <li><a href="#3" data-toggle="tab">Options</a></li>
        <li><a href="#4" data-toggle="tab">Addon</a></li>
        <li><a href="#5" data-toggle="tab">Question</a></li>
        <li><a href="#6" data-toggle="tab">Gallery</a></li>
        <?php if($price_table) { ?>
        <li><a href="#7" data-toggle="tab">Price Table</a></li>
        <?php } ?>
        <li><a href="#8" data-toggle="tab">Videos</a></li>
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
                            'label' => $vendor_item->quantity_label != $model->quantity_label ? 'Quantity Label *' : 'Quantity Label',
                            'value' => $model->quantity_label,
                        ],
                        [
                            'label' => $vendor_item->set_up_time != $model->set_up_time ? 'Setup Time *' : 'Setup Time',
                            'value' => $model->set_up_time,
                        ],
                        [
                            'label' => $vendor_item->set_up_time_ar != $model->set_up_time_ar ? 'Setup Time - Arabic *' : 'Setup Time - Arabic',
                            'value' => $model->set_up_time_ar,
                        ],
                        [
                            'label' => $vendor_item->max_time != $model->max_time ? 'Duration *' : 'Duration',
                            'value' => $model->max_time,
                        ],
                        [
                            'label' => $vendor_item->max_time_ar != $model->max_time_ar ? 'Duration - Arabic *' : 'Duration - Arabic',
                            'value' => $model->max_time_ar,
                        ],
                        [
                            'label' => $vendor_item->requirements != $model->requirements ? 'Requirements *' : 'Requirements',
                            'value' => $model->requirements,
                        ],
                        [
                            'label' => $vendor_item->requirements_ar != $model->requirements_ar ? 'Requirements - Arabic *' : 'Requirements - Arabic',
                            'value' => $model->requirements_ar,
                        ],
                        [
                            'label' => $vendor_item->min_order_amount != $model->min_order_amount ? 'Min. Order KD *' : 'Min. Order KD',
                            'value' => $model->min_order_amount,
                        ],
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
                        [
                            'label' => $vendor_item->item_how_long_to_make != $model->item_how_long_to_make ? 'ITEM HOW LONG TO MAKE *' : 'ITEM HOW LONG TO MAKE',
                            'value' => strip_tags($model->item_how_long_to_make),
                        ],
                        [
                            'label' => ($vendor_item->item_minimum_quantity_to_order != $model->item_minimum_quantity_to_order) ? 'Minimum quantity to order *' : 'Minimum quantity to order',
                            'value' => strip_tags($model->item_minimum_quantity_to_order),
                        ],
                        [
                            'label' => ($vendor_item->included_quantity != $model->included_quantity) ? 'Included Quantity *' : 'Included Quantity',
                            'value' => strip_tags($model->included_quantity),
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
                            'label' => $vendor_item->item_price_per_unit != $model->item_price_per_unit ? 'ITEM INCREMENT PRICE PER UNIT *' : 'ITEM INCREMENT PRICE PER UNIT',
                            'value' => $model->item_price_per_unit
                        ],
                        [
                            'label' => $vendor_item->item_base_price != $model->item_base_price ? 'ITEM BASE PRICE *' : 'ITEM BASE PRICE',
                            'value' => $model->item_base_price
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
          <ul id="item_addon_menu_list">
          <?php foreach ($arr_menu as $key => $value) { 

            $current_menu = VendorItemMenu::findOne($value->menu_id);

            ?>
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

                      <?php if(!$current_menu || $current_menu->menu_name != $value->menu_name) { ?>
                        *
                      <?php } ?>
                    </td>
                    <td class="required">
                      <?= $value->menu_name_ar ?>
                      <?php if(!$current_menu || $current_menu->menu_name_ar != $value->menu_name_ar) { ?>
                        *
                      <?php } ?>
                    </td>
                    <td>
                      <?= $value->min_quantity ?>
                      <?php if(!$current_menu || $current_menu->min_quantity != $value->min_quantity) { ?>
                        *
                      <?php } ?>
                    </td>
                    <td>
                      <?= $value->max_quantity ?>
                      <?php if(!$current_menu || $current_menu->max_quantity != $value->max_quantity) { ?>
                        *
                      <?php } ?>
                    </td>
                    <td>
                      <?= $value->quantity_type ?>
                      <?php if(!$current_menu || $current_menu->quantity_type != $value->quantity_type) { ?>
                        *
                      <?php } ?>
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

                  $arr_menu_item = VendorDraftItemMenuItem::findAll(['draft_menu_id' => $value->draft_menu_id]);

                  foreach ($arr_menu_item as $key => $menu_item) { 

                    $current_menu_item = VendorItemMenuItem::findOne($menu_item->menu_item_id);

                    ?>
                  <tr>
                    <td class="required">
                      <?= $menu_item->menu_item_name ?>

                      <?php if(!$current_menu_item || $current_menu_item->menu_item_name != $menu_item->menu_item_name) { ?>
                        *
                      <?php } ?>

                    </td>
                    <td class="required">
                      <?= $menu_item->menu_item_name_ar ?>

                      <?php if(!$current_menu_item || $current_menu_item->menu_item_name_ar != $menu_item->menu_item_name_ar) { ?>
                        *
                      <?php } ?>
                    </td>
                    <td>
                      <?= $menu_item->hint ?>

                      <?php if(!$current_menu_item || $current_menu_item->hint != $menu_item->hint) { ?>
                        *
                      <?php } ?>
                    </td>
                    <td>
                      <?= $menu_item->hint_ar ?>

                      <?php if(!$current_menu_item || $current_menu_item->hint_ar != $menu_item->hint_ar) { ?>
                        *
                      <?php } ?>
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

          <?php foreach ($arr_addon_menu as $key => $value) { 
            
                $current_menu = VendorItemMenu::findOne($value->menu_id);

            ?>
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

                    <?php if(!$current_menu || $current_menu->menu_name != $value->menu_name) { ?>
                        *
                    <?php } ?>
                  </td>
                  <td>
                    <?= $value->menu_name_ar ?>

                     <?php if(!$current_menu || $current_menu->menu_name_ar != $value->menu_name_ar) { ?>
                        *
                    <?php } ?>
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

                $arr_menu_item = VendorDraftItemMenuItem::findAll(['draft_menu_id' => $value->draft_menu_id]);

                foreach ($arr_menu_item as $key => $menu_item) { 

                  $current_menu_item = VendorItemMenuItem::findOne($menu_item->menu_item_id);

                  ?>
                <tr>
                  <td>
                    <?= $menu_item->menu_item_name ?>

                    <?php if(!$current_menu_item || $current_menu_item->menu_item_name != $menu_item->menu_item_name) { ?>
                      *
                    <?php } ?>
                  </td>
                  <td>
                    <?= $menu_item->menu_item_name_ar ?>

                    <?php if(!$current_menu_item || $current_menu_item->menu_item_name_ar != $menu_item->menu_item_name_ar) { ?>
                      *
                    <?php } ?>
                  </td>
                  <td>
                    <?= $menu_item->price ?>

                    <?php if(!$current_menu_item || $current_menu_item->price != $menu_item->price) { ?>
                      *
                    <?php } ?>
                  </td>
                  <td>
                    <?= $menu_item->hint ?>

                    <?php if(!$current_menu_item || $current_menu_item->hint != $menu_item->hint) { ?>
                      *
                    <?php } ?>
                  </td>
                  <td>
                    <?= $menu_item->hint_ar ?>

                    <?php if(!$current_menu_item || $current_menu_item->hint_ar != $menu_item->hint_ar) { ?>
                      *
                    <?php } ?>
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

        <div class="tab-pane" id="6">
            <ul class="row">
                <?php foreach ($imagedata as $key => $image) {
                    
                    $alias = Yii::getAlias('@vendor_item_images_210/');

                    //check if already in item 
                    $exists = Image::findOne(['item_id' => $model->item_id, 'image_path' => $image->image_path]);

                    ?>
                    <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                        
                        <?php if(!$exists) {
                            echo '*';
                        } ?>

                        <?= Html::img($alias.$image->image_path, ['style'=>'width:140px;height:140px;', 'class'=>'img-gallery img-responsive ', 'id' => 'image-'.$key,'alt'=>'Gallery','data-img'=>Yii::getAlias('@web/uploads/vendor_images/').$image->image_path]); ?>

                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="tab-pane" id="5">
            <table class="table table-striped table-bordered detail-view">
                <tbody>
                <?php if ($questions) {
                    $i=1;
                    foreach ($questions as $question) { ?>
                        <tr><th>Question <?=$i?></th><td><?=$question->question?></td></tr>
                        <?php
                        $i++;
                    }
                } else { ?>
                    <tr><td colspan="2" align="center">No Question Found</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="7">
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

        <div class="tab-pane" id="8">
            <ul class="row">
                <?php foreach ($videos as $video) { ?>
                    <li class="col-lg-4 col-md-4">
                        <a href="https://www.youtube.com/watch?v=<?= $video->video ?>" target="_blank"> 
                            <?= Html::img('https://img.youtube.com/vi/'.$video->video.'/hqdefault.jpg', ['style' => 'width:100%;', 'alt'=>'item detail video']) ?>
                        </a>
                    </li>
                <?php } ?>    
            </ul>
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

$this->registerJsFile('@web/themes/default/plugins/bootstrap-modal-box/photo-gallery.js?v=1.0', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_draft_item.js?v=1.1", ['depends' => [\yii\web\JqueryAsset::className()]]);
