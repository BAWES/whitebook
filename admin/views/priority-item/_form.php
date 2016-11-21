<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$request = Yii::$app->request;

?>
<div class="loadingmessage" style="display: none;">
<p>
<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
</p>
</div>

    <?php $form = ActiveForm::begin(array('options' => array('id' => 'formId','name' => 'formId'),'enableClientValidation'=>false)); ?>

  <div class="col-md-8 col-sm-8 col-xs-8">
  
  <?php if($model->isNewRecord) {
    echo $form->field($model, 'item_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
    ])->dropDownList($priorityitem,['prompt'=>'Select...']); 
    } else {
     echo $form->field($model, 'item_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
    ])->dropDownList($priorityitem);
    }
    ?>


    <?= $form->field($model, 'priority_level')->dropDownList([ 'Normal' => 'Normal', 'Super' => 'Super', ], ['prompt' => 'Select']) ?>

        <?php if($model->isNewRecord){?>
      <div class="form-group">
        <?= $form->field($model, 'priority_start_date',[ 'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
    ])->textInput([['maxlenght' => 255,]]) ?>
        </div>
        <?php }else { ?>
          <div class="form-group">
    <?= $form->field($model, 'priority_start_date',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
    ])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->priority_start_date ) )]) ?>
    </div>
        <?php }?>

    <?php if($model->isNewRecord){?>
      <div class="form-group">
        <?= $form->field($model, 'priority_end_date',[ 'template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
    ])->textInput([['maxlength' => 255,]]) ?>
        </div>
        <?php }else { ?>
          <div class="form-group">
    <?= $form->field($model, 'priority_end_date',['template' => "{label}<div class='controls'>{input}</div> {hint} {error}"
    ])->textInput(['maxlenght' => 255, 'value' => date( 'd-m-Y', strtotime( $model->priority_end_date ) )]) ?>
    </div>
        <?php }?>

    <div id="blocked_error" style="color:red">Blocked dates available in between start date and end date</div>
    <input type="hidden" name="blocked_dates" id="blocked_dates" value="">
    <div class="form-group">
    <?php if($model->priority_id){?>
           <input type="button" name="submit1" id="submit1" value="Update" class="btn btn-primary" />
    <?php } else { ?>
        <input type="button" name="submit1" id="submit1" value="Create" class="btn btn-success" />
        <?php } ?>
       <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

if($request->get('id')) {
    $priority_id = $request->get('id');
}else{
    $priority_id = 0;
}

if($model->isNewRecord) {
    $is_new_record = 1;
}else{
    $is_new_record = 0;
}

$this->registerJs("
    var priority_id = '".$priority_id."';
    var is_new_record = ".$is_new_record.";
", View::POS_HEAD);

echo Html::hiddenInput('load_item_url', Url::to(['/priority-item/loaditems']), ['id' => 'load_item_url']);

echo Html::hiddenInput('load_date_time_url', Url::to(['/priority-item/loaddatetime']), ['id' => 'load_date_time_url']);

echo Html::hiddenInput('check_priority_date_url', Url::to(['/priority-item/checkprioritydate']), ['id' => 'check_priority_date_url']);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css');

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('@web/themes/default/js/priority_item.js?v=1.1', ['depends' => [\yii\web\JqueryAsset::className()]]);

