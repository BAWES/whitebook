<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Package */

$this->title = $model->package_id;
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->package_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->package_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1" data-toggle="tab">Package Info </a>
            </li>
            <li>
                <a href="#tab_2" data-toggle="tab">Items</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="thumbnail">
                            <img src="<?= Yii::getAlias('@s3').'/'.$model->package_background_image ?>" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'package_id',
                                'package_name',
                                'package_description:ntext',
                                'package_avg_price',
                                'package_number_of_guests',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_2">
                <div class="row">
                    <div class="box_available col-lg-4">
                        <div class="heading">
                            Available 
                        </div>
                        <div class="search_box">
                            <input type="text" placeholder="Search..." />
                        </div>
                        <div class="item_list">
                            <ul>
                                <?php foreach ($items as $key => $value) { ?>
                                <li data-id="<?= $value->item_id ?>">     
                                    <?php 

                                    if($value->vendor) {                       
                                        echo $value->vendor->vendor_name.' - '; 
                                    }

                                    echo $value->item_name .' #'.$value->item_id;

                                    ?>
                                </li>
                                <?php } ?>                            
                            </ul>
                        </div>
                    </div><!-- END .box_available -->

                    <div class="box_selected col-lg-4">
                        <div class="heading">
                            Selected 
                        </div>
                        <div class="search_box">
                            <input type="text" placeholder="Search..." />
                        </div>
                        <div class="item_list">
                            <form id='package_items'>
                            <ul>
                                <?php foreach ($selected_items as $key => $value) { ?>
                                <li>                                
                                    <?php 

                                    if($value->vendor) {                       
                                        echo $value->vendor->vendor_name.' - '; 
                                    }

                                    echo $value->item_name;

                                    ?>
                                    <input type="hidden" name="items[]" value="<?= $value->item_id ?>" />
                                    <i class="fa fa-close"></i>
                                </li>
                                <?php } ?>                            
                            </ul>
                            </form>
                        </div>
                    </div><!-- END .box_selected -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

echo Html::hiddenInput('update_item_url', Url::to(['package/update-item', 'id'=> $model->package_id]), ['id' => 'update_item_url']);

$this->registerJsFile("@web/themes/default/js/package_view.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
