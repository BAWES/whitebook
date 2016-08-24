<style>
    .datepicker{
        border: 2px solid rgb(242, 242, 242);
    }
    .datepicker table {
        font-size: 12px;
    }
    .form-group{
        margin-bottom: 15px;
        width: 92%;
        margin-left: 11px;
    }
</style>
<div class="panel panel-default" >
    <div class="panel-heading">
        <div class="clear_left"><p><?= Yii::t('frontend', 'Delivery date');?><a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
        <div class="clear_right">
            <a href="#date-filter" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
                <h4 class="panel-title">
                    <span class="plus_acc"></span>
                </h4>
            </a>
        </div>
    </div>
    <div id="date-filter" class="panel-collapse collapse" aria-expanded="false">
        <div class="panel-body">
            <div class="table">
                <div class="form-group">
                    <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date" style="position: relative;"  id="delivery_date_wrapper">
                        <input readonly="true" name="delivery_date" id="delivery_date" class="form-control required"  placeholder="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>" style="height: 40px;">
                        <span class="add-on position_product_option"> <i class="flaticon-calendar189"></i></span>
                    </div>
                    <span class="error cart_delivery_date"></span>
                </div>
            </div>
        </div>
    </div>
</div>