<?php

$a = Yii::$app->request->get('event_time');

if($a) {
    Yii::$app->session->set('event_time', $a);
}

$event_time = Yii::$app->session->get('event_time');

$arr_time = ['12:00', '12:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00',
          '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
          '11:00', '11:30'];
?>

<div class="panel panel-default" id="top_panel_event_time">
    <div class="panel-heading clearfix" id="top_panel_heading">
        <div class=""><p><?= Yii::t('frontend', 'Event Time');?><a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
    </div>
    <div id="event-time" class="panel-collapse " aria-expanded="false">
        <div class="">
            <div class="form-group">
                <select id="event_time" class="selectpicker" data-live-search="false" data-size="10" data-placeholder="">
                    <option value="" class="label"><?= Yii::t('frontend', 'Choose Your Event Time') ?></option>
                    <optgroup label="am">                        
                        <?php foreach ($arr_time as $key => $value) { 
                            if($value.' am' == $event_time) 
                                $selected = 'selected'; 
                            else
                                $selected = ''; ?>
                            <option value="<?= $value ?> am" data-content="<?= $value ?> <span>am</span>" <?= $selected ?>>
                                <?= $value ?>
                            </option>
                        <?php } ?>
                    </optgroup>
                    <optgroup label="pm">                        
                        <?php foreach ($arr_time as $key => $value) { 
                            if($value.' pm' == $event_time) 
                                $selected = 'selected'; 
                            else
                                $selected = ''; ?>
                            <option value="<?= $value ?> pm" data-content="<?= $value ?> <span>pm</span>">
                                <?= $value ?>
                            </option>
                        <?php } ?>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJS("

        $('#event_time').on('changed.bs.select', function (e) {
          filter();
        });
        
    ",\yii\web\View::POS_READY) ?>
