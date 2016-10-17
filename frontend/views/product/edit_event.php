<?php

use yii\helpers\Html;
use frontend\models\Website;
?>
<!-- BEGIN Create event Modal Box -->
<div class="modal-dialog">
    <div class="modal-content  modal_member_login signup_poupu row">
        <div class="modal-header">
            <button type="button" class="close" id="boxclose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="text-center">
                <span class="yellow_top"></span>
            </div>
            <h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend', 'Edit Event'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-8 col-xs-offset-2">
                    <div class="product_popup_signup_box">
                        <div class="product_popup_signup_log">
                            <form name="update_event" id="update_event">
                                <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
                                <div class="form-group">
                                    <input type="hidden" name="event_id" class="form-control" id="edit_event_id" value=<?= $edit_eventinfo[0]['event_id']; ?>>
                                    <input type="text" name="event_name" class="form-control required" id="edit_event_name" value="<?php echo $edit_eventinfo[0]['event_name']; ?>" placeholder="<?php echo Yii::t('frontend', 'Enter Event Name'); ?>" title="<?php echo Yii::t('frontend', 'Enter Event Name'); ?>">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="item_id" class="form-control required" id="item_id" value="0">
                                </div>
                                <div class="form-group">
                                    <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="eventdate_icon" class="input-append date">
                                        <input readonly="true" name="event_date" id="edit_event_date" class="form-control required datetimepicker" value=<?= date('d-m-Y', strtotime($edit_eventinfo[0]['event_date'])); ?> placeholder="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>">
                                        <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                                    </div>
                                    <label for="event_date" class="error_validate"></label>
                                </div>
                                <div class="form-group new_popup_common">
                                    <div class="bs-docs-example"><select class="selectpicker required trigger" name="event_typee" data="" style="btn-primary" id="edit_event_type" >
                                            <option value="">Select event type</option>
                                            <?php
                                            $event_type = Website::get_event_types();
                                            foreach ($event_type as $e) {
                                                ?>
                                                <option value="<?php echo $e['type_name']; ?>" <?php if ($edit_eventinfo[0]['event_type'] == $e['type_name']) {
                                                echo "selected=selected";
                                            } else {
                                                echo '';
                                            } ?>><?php echo $e['type_name']; ?></option>
<?php } ?>
                                        </select>

                                        <div class="error" id="type_error"></div>
                                    </div>
                                </div>
                                <div id="eventresult" style="color:red"></div>
                                <div class="eventErrorMsg error" style="color:red;margin-bottom: 10px;"></div>
                                <div class="event_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo yii\helpers\Url::to("@web/images/ajax-loader.gif"); ?>" title="Loader"></div>
                                <div class="buttons">
                                    <div class="creat_evn_sig">
                                        <button type="button" id="update_event_button" name="update_event_button" class="btn btn-default" title="<?php echo Yii::t('frontend', 'Update Event'); ?>"><?php echo Yii::t('frontend', 'Update Event'); ?></button>
                                    </div>
                                    <div class="cancel_sig">
                                        <input class="btn btn-default" data-dismiss="modal" type="button" value="<?php echo Yii::t('frontend', 'Cancel'); ?>" title="<?php echo Yii::t('frontend', 'Cancel'); ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Create event Modal Box -->
<script type="text/javascript">
    jQuery('#eventdate_icon').datepicker({
        format: 'dd-mm-yyyy',
        startDate: 'today',
        autoclose: true,
    });
</script>
