<?php

use yii\helpers\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse"  href="#collapse-options" aria-expanded="true">
            <?= Yii::t('frontend', 'Availability') ?>
        </a>
      </h4>
    </div>
    <div id="collapse-options" class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="col-md-12 filter-bar ">
            <div class="col-md-4 padding-right-0 area-filter">
                <div class="form-group margin-left-0">
                    <label><?=Yii::t('frontend', 'Event Area'); ?></label>
                    <div class="select_boxes">
                        <?php
                            echo Html::dropDownList('area_id', $deliver_location,
                            $vendor_area,
                            ['data-height'=>"100px",'data-live-search'=>"true",'id'=>"area_id", 'class'=>"selectpicker", 'data-size'=>"10", 'data-style'=>"btn-primary"]);
                        ?>
                    </div>
                    <span class="error area_id"></span>
                </div>
            </div>
            <div class="col-md-3 padding-left-0 delivery-date-filter">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Event Date'); ?></label>
                    <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date" id="delivery_date_wrapper">
                        <input value="<?= $deliver_date ?>" readonly="true" name="delivery_date" id="item_delivery_date" class="date-picker-box form-control required"  placeholder="<?php echo Yii::t('frontend', 'Date'); ?>" >
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                    <span class="error cart_delivery_date"></span>
                </div>
            </div>
            <div class="col-md-5 padding-left-0 timeslot_id_div timeslot-filter">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Event Time'); ?></label>
                    <div class="text padding-top-12"><?=Yii::t('frontend','Please Select Valid Event Date');?></div>
                </div>
            </div>
            <div class="col-md-5 padding-left-0 timeslot_id_select timeslot-filter" style="display: none;">
                <div class="form-group">
                    <label><?=Yii::t('frontend', 'Event Time'); ?></label>
                    <select name="time_slot" id="timeslot_id" class="selectpicker" data-size="10" data-style="btn-primary"></select>
                    <span class="error timeslot_id"></span>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>