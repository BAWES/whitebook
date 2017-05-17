<div class="panel panel-default" id="top_panel_date">
    <div class="panel-heading clearfix" id="top_panel_heading">
        <div class="">
            <p>
                <?=Yii::t('frontend', 'Delivery Date');?>
                <a href="javascript:void(0)" style="<?=($deliver_date)?'display:inline':'';?>" class="filter-clear" id="filter-clear-date" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a>
            </p>
        </div>
    </div>
    <div id="date-filter" class="panel-collapse" aria-expanded="false">
        <div class="panel-body">
            <div class="table">
                <div class="form-group">
                    <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date postion-relative" id="delivery_date_wrapper">
                        <input value="<?=$deliver_date; ?>" readonly="true" name="delivery_date" id="delivery_date_2" class="form-control required"  placeholder="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                        <span class="add-on position_product_option"> <i class="flaticon-calendar189"></i></span>
                    </div>
                    <span class="error cart_delivery_date"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerCss("
.datepicker{
        border: 2px solid rgb(242, 242, 242);
    }
    .datepicker table {
        font-size: 12px;
    }

    #top_panel_date {
        border-bottom: none;
        border-top: none;
        border: none;
        border-radius: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
        margin-bottom: 0px;
    }
    #top_panel_heading {border: none;box-shadow: none;}
    #top_panel_date .form-group,#top_panel_date .clear_left{
        margin-left:0px;padding-left:0px;
        margin-right:0px;padding-right:0px
    }
    .postion-relative{
        position: relative;
    }
    #delivery_date_2{height: 40px;     border-radius: 0px;box-shadow: none;}
"); ?>