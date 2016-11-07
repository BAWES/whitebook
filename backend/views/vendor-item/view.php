<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritem */

$this->title = 'Item details';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12 col-sm-12 col-xs-12">
<!-- Begin Twitter Tabs-->
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#1" data-toggle="tab">Item info</a></li>
            <li><a href="#2" data-toggle="tab">Item description</a></li>
            <li><a href="#3" data-toggle="tab">Item price</a></li>
            <li><a href="#4" data-toggle="tab">Priority Log</a></li>
            <?php if($model->item_for_sale=='Yes') { ?>
                <li><a href="#5" data-toggle="tab">Question Answer Details</a></li>
            <?php } ?>
            <li><a href="#6" data-toggle="tab">Gallery</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" id="1" >
                <table class="table table-striped table-bordered detail-view">
                    <tbody>
                        <tr><th>Item name</th><td><?= $model->item_name ?></td></tr>
                        <tr><th>Item name - Arabic</th><td><?= $model->item_name_ar ?></td></tr>
                        <tr>
                            <th>Categories</th>
                            <td>
                              <ul>
                                  <?php foreach($categories as $key => $value) { ?>
                                      <li><?= $value->category->category_title ?></li>
                                  <?php } ?>
                              </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="2" >
                <table class="table table-striped table-bordered detail-view">
                    <tbody>
                        <tr><th>Item type</th><td><?= $item_type; ?></td></tr>
                        <tr><th>Item description</th><td><?= $model->item_description; ?></td></tr>
                        <tr><th>Item description - Arabic</th><td><?= $model->item_description_ar; ?></td></tr>
                        <tr><th>Item additional info</th><td><?= $model->item_additional_info; ?></td></tr>
                        <tr><th>Item additional info - Arabic</th><td><?= $model->item_additional_info_ar; ?></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="3" >
                <table class="table table-striped table-bordered detail-view">
                    <tbody>
                        <tr><th>Item for sale</th><td><?= $model->item_for_sale?'Yes':'No'; ?></td></tr>
                        <tr><th>Item Number of Stock</th><td><?= $model->item_amount_in_stock; ?></td></tr>
                        <tr><th>Item Default Capacity</th><td><?= $model->item_default_capacity; ?></td></tr>
                        <tr><th>No of days delivery</th><td><?= $model->item_how_long_to_make ?></td></tr>
                        <tr><th>Item Minimum Quantity to Order</th><td><?= $model->item_minimum_quantity_to_order ?></td></tr>
                        <tr><th>Item Price per Unit</th><td><?= $model->item_price_per_unit ?></td></tr>
                        <tr>
                            <th>Item price</th>
                            <td>
                                <table>
                                    <tr><th>From range</th><th>To range</th><th>Price</th></tr>
                                    <?php foreach ($price_values as $row) { ?>
                                        <tr>
                                            <td><?= $row->range_from ?></td>
                                            <td><?= $row->range_to ?></td>
                                            <td><?= $row->pricing_price_per_unit ?> KWD</td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                        <tr><th>Item price description</th><td><?= $model->item_price_description ?></td></tr>
                        <tr><th>Item price description - Arabic</th><td><?= $model->item_price_description_ar ?></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="4" >
                <table class="table table-striped table-bordered detail-view">
                    <tbody>
                        <tr><th>Priority Level</th><th>Start Date</th><th>End Date</th></tr>
                        <?php $model->item_id; foreach ($dataProvider1 as $log) { ?>
                            <tr><td><?php print_r($log['priority_level']);?></td><td><?php $sd=($log['priority_start_date'][0]);$sd=($log['priority_start_date']); echo date("d/m/Y", strtotime($sd)); ?></td><td><?php $sd=($log['priority_end_date']);if($sd=='0000-00-00 00:00:00'){echo 'not set';}else { echo date("d/m/Y", strtotime($sd));} ?></td></tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <?php if($model->item_for_sale=='Yes'){ ?>
                <div class="tab-pane" id="5">
                    <?php
                    $t=0;
                    foreach($model_question as $question_records) { ?>
                            <div class="form-group superbox" id="delete_<?= $t;?>">
                            <div class="form-group superbox-s" id="delete_<?= $t;?>">
                                <li class="parent_question">
                                    <?= ucfirst($question_records['question_text']); ?>
                                    <span  class="plus"><a href="#" onclick="questionView('<?= $question_records['question_id']; ?>',this)"></a></span><div class="show_ques<?= $question_records['question_id']; ?>"></div>
                                </li>
                            </div>
                        </div>
                        <?php $t++;
                    }
                    ?>
                </div>
            <?php } ?>
            <div class="tab-pane" id="6" >
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
        </div>
    </div>
</div>
<?php $this->registerCss("
      ul {padding:0 0 0 0;margin:0 0 0 0;}
      ul li {list-style:none;margin-bottom:25px;}
      ul li img {cursor: pointer;}
      .modal-body {padding:5px !important;}
      .modal-content {border-radius:0;}
      .modal-dialog img {text-align:center;margin:0 auto;}
      .controls{width:50px;display:block;font-size:11px;padding-top:8px;font-weight:bold;}
      .next {float:right;text-align:right;}
      /*override modal for demo only*/
      .modal-dialog {max-width:500px;padding-top: 90px;}
      @media screen and (min-width: 768px){.modal-dialog {width:500px;padding-top: 90px;}}
      @media screen and (max-width:1500px){#ads {display:none;}}
  ");

$this->registerJs("
    var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');

    // single question view
    function questionView(q_id,tis) {
        var check = $('.show_ques'+q_id).html();
        if (check=='') {
            var path = '".Url::to(['vendoritem/viewrenderquestion'])."';
            $.ajax({
                type : 'POST',
                url :  path,
                data: { q_id: q_id ,_csrf : csrfToken},
                success: function( data ) {
                $('.show_ques'+q_id).html(data);
                $(this).toggleClass('expanded');
                return false;
                }
            });
        } else {
            $('.show_ques'+q_id).toggle();
            $(this).toggleClass('expanded');
        }
    }
    /* Begin when loading page first tab opened */
    $('.nav-tabs li:first').addClass('active');
    $('.tab-content div:first').addClass('active');
");

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/themes/default/plugins/bootstrap-modal-box/photo-gallery.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="z-index: 99999;">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>