<?php
    use yii\helpers\Html;
    $weekDays = ['Sunday','Monday','Tuesday','Wednesday', 'Thursday', 'Friday', 'Saturday'];

    // data manipulation for working day
    $dummyArray = [];
    if ($workingDay) {
        foreach ($workingDay as $days) {
            $dummyArray[$days['working_day']] = $days;
        }
    }
?>
<table class="table table-bordered table-social">
    <thead>
        <tr>
            <th>Week Days</th>
            <th>Working Start Timing</th>
            <th>Working End Timing</th>
            <th>Day Off</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($weekDays as $key => $day) {
        $dayOff = false;
        $startTime = $endTime = '';
        if (isset($dummyArray[$day])) {
            $startTime = $dummyArray[$day]['working_start_time'];
            $endTime = $dummyArray[$day]['working_end_time'];
        } else if ($dummyArray) {
            $dayOff = true;
        }
        ?>
        <tr>
            <td><?=$day?></td>
            <td class="working_hours_wrapper">
                <input type="text" id="vendor_working_start_time_<?=$key?>" class="form-control" name="vendor_working_start_time[<?=$day?>]" value="<?=$startTime?>" placeholder="Opening Time">
            </td>
            <td class="working_hours_wrapper">
                <input type="text" id="vendor_working_end_time_<?=$key?>" class="form-control" name="vendor_working_end_time[<?=$day?>]" value="<?=$endTime?>" placeholder="Closing Time">
            </td>
            <td>
                <input type="checkbox" name="vendor_non_working_day[<?=$day?>]" value="<?=$day?>" <?=($dayOff) ? "checked='checked'":'' ?> id="day_<?=$key?>" />
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="clearfix">
    <div class="col-md-4"><input type="button" name="btnPrevious" class="btnPrevious btn btn-info" value="Prev"></div>
    <div class="col-md-4 text-center"><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success submit_btn' : 'btn btn-primary submit_btn']) ?></div>
    <div class="col-md-4"><input type="button" name="btnNext" class="btnNext btn btn-info" value="Next"></div>
</div>
