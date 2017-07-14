<div class="panel panel-default" id="notice_period_filter">
    <div class="panel-heading">
        <div class="clear_left">
            <p>
                <?= Yii::t('frontend', 'Notice Period'); ?>
            </p>
        </div>
        <div class="clear_right">
            <a href="#notice_period" data-parent="#accordion" data-toggle="collapse" class="collapsed">
                <h4 class="panel-title">
                    <span class="plus_acc"></span>
                </h4>
            </a>
        </div>
    </div>
    <div class="panel-collapse collapse" id="notice_period" area-expanded="true">
        <div class="panel-body" style="padding: 5px;">
            <div class="pull-left" style="width: 35%;">
                <input type="number" value="<?= Yii::$app->request->get('notice_period_from') ?>" class="form-control notice_period_from" placeholder="<?= Yii::t('frontend', 'From') ?>" onKeyUp="filter();" />
            </div>
            <div class="pull-left" style="width: 35%;">
                <input type="number" value="<?= Yii::$app->request->get('notice_period_to') ?>" class="form-control notice_period_to" placeholder="<?= Yii::t('frontend', 'To') ?>" onKeyUp="filter();" />
            </div>
            <div class="pull-left" style="width: 30%;" onChange="filter();">    
                <select class="form-control notice_period_type">
                    <option value="Day"><?= Yii::t('frontend', 'Day') ?></option>
                    <?php if(Yii::$app->request->get('notice_period_type') == 'Hour') { ?>"
                        <option value="Hour" selected><?= Yii::t('frontend', 'Hour') ?></option>
                    <?php } else { ?>    
                        <option value="Hour"><?= Yii::t('frontend', 'Hour') ?></option>
                    <?php } ?>    
                </select>
            </div>    
        </div><!-- END .panel-body -->
    </div><!-- END .panel-collapse -->
</div><!-- END .panel -->
