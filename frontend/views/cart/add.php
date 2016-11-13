<?php 

use common\models\Location;
use common\components\LangFormat;
?>
<div class="modal fade" id="productOptionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content row">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel">
                    <span><?= Yii::t('frontend', 'OPTIONS') ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <form id="form_product_option" method="POST" class="form col-md-12 center-block">

                    <div class="login-padding">
                        
                        <input name="item_id" value="<?= $item_id ?>" type="hidden" />


                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'Area') ?></label>
                            <div class="select_boxes">
                                <select name="area_id" data-height="100px" data-live-search="true" id="area_id" class="selectpicker" data-size="10" data-style="btn-primary">
                                   
                                    <?php 

                                    foreach ($cities as $city) {
                                        $city_name = LangFormat::format($city['city_name'],$city['city_name_ar']);

                                        //get areas
                                        $areas = Location::find()
                                            ->select('id, location, location_ar')
                                            ->where(['city_id' => $city['city_id']])
                                            ->asArray()
                                            ->all();

                                    ?>
                                        <optgroup label="<?= $city_name ?>">

                                        <?php

                                        foreach ($areas as $area) {
                                            $area_name = LangFormat::format($area['location'],$area['location_ar']);
                                            ?>
                                                <option value="<?= $area['id'] ?>">
                                                    <?= $area_name; ?>
                                                </option>
                                        <?php } ?>

                                        </optgroup>

                                    <?php
                                    } ?>
                                </select>                                
                            </div>    
                            <span class="error area_id"></span>
                        </div>
                        
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'Delivery date');?></label>
                            <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" class="input-append date poistion-relative"  id="delivery_date_wrapper">
                                <input readonly="true" name="delivery_date" id="delivery_date" class="form-control required"  placeholder="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>" style="height: 40px;">
                                <span class="add-on position_product_option"> <i class="flaticon-calendar189"></i></span>
                            </div>
                            <span class="error cart_delivery_date"></span>
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'Delivery timeslot');?></label>
                            <select name="timeslot_id" id="timeslot_id" class="selectpicker" data-size="10" data-style="btn-primary">
                            </select>
                            <span class="error timeslot_id"></span>
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'Quantity');?></label>
                            <input type="text" name="quantity" id="quantity" class="form-control" />
                            <span class="error cart_quantity"></span>
                        </div>
                        <div class="button-signin">
                            <button type="submit" class="btn btn-primary btn-custome-1" name="submit">
                                <?= Yii::t('frontend', 'Submit') ?>
                            </button>
                        </div>
                    </div>
                </form>

            </div>            
        </div>
    </div>
</div>
<?php

$this->registerCss("
    .poistion-relative{position: relative;}
");