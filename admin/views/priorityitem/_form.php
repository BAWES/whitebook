<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<div class="loadingmessage" style="display: none;">
<p>
<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
</p>
</div>

    <?php $form = ActiveForm::begin(array('options' => array('id' => 'formId','name' => 'formId'),'enableClientValidation'=>false)); ?>
    <h4>    Category filter</h4>
    <?= $form->field($model, 'category_id',['template' => "<div class='controls'>{input}</div> {hint} {error}"
    ])->dropDownList($category, ['prompt'=>'Select category...','class'=>'filter','style'=>'float:left;margin-left: 15px;  width: 215px;  margin-top: 10px;']); ?>

    <?= $form->field($model, 'subcategory_id',['template' => "<div class='controls'>{input}</div> {hint} {error}"
    ])->dropDownList($subcategory, ['prompt'=>'Select sub category...','class'=>'filter','style'=>'float:left;margin-left: 5px;  width: 215px;']); ?>

    <?= $form->field($model, 'child_category',['template' => "<div class='controls'>{input}</div> {hint} {error}"
    ])->dropDownList($childcategory, ['prompt'=>'Select child category...','class'=>'filter','style'=>'float:left;margin-left: 5px;  width: 215px;']);?>

  <div class="col-md-8 col-sm-8 col-xs-8">
  <br><br/>
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

<script type="text/javascript">
$('#blocked_error').hide();
var csrfToken = $('meta[name="csrf-token"]').attr("content");
$(function (){
    $("#priorityitem-category_id").change(function (){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id = $('#priorityitem-category_id').val();
        var path = "<?php echo Url::to(['/priorityitem/loadsubcategory']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
             $('#priorityitem-subcategory_id').html(data);
         }
        })

     });
 });

</script>

<!-- BEGIN PLUGIN CSS -->

<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->




<script>
//* Load Sub Category *//
$(function (){
    $("#priorityitem-category_id").change(function (){
        var id = $('#priorityitem-category_id').val();
        var path = "<?php echo Url::to(['/priorityitem/loadsubcategory']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
             $('#priorityitem-subcategory_id').html(data);
         }
        })
     });
 });

//* Load Child Category *//
$(function (){
    $("#priorityitem-subcategory_id").change(function (){
        var id = $('#priorityitem-subcategory_id').val();
        var path = "<?php echo Url::to(['/priorityitem/loadchildcategory']); ?> ";
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { id: id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
            $('#priorityitem-child_category').html(data);
         }
        })
     });
 });
</script>

<script type="text/javascript">
$(function (){
    $("#priorityitem-child_category").change(function (){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var id2 = $('#priorityitem-category_id').val();
        var id3 = $('#priorityitem-subcategory_id').val();
        var id4 = $('#priorityitem-child_category').val();
        $('.loadingmessage').show();
        var path = "<?php echo Url::to(['/priorityitem/loaditems']); ?> ";
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { id2: id2 ,id3: id3 ,id4: id4 ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
             $('#priorityitem-item_id').html(data);
         }
        })
     });
 });

</script>

<script>
$(function (){
    $("#priorityitem-item_id").on("change",function (){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var item = $('#priorityitem-item_id').val();
        var path = "<?php echo Url::to(['/priorityitem/loaddatetime']); ?> ";
        var priority_id = <?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>;
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        dataType:"json",
        url: path, //url to be called
        data: { item: item ,priority_id : priority_id,_csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
            $('.field-priorityitem-priority_start_date').find('input').remove();
            $('.field-priorityitem-priority_start_date').find('label').after(data.input1);
            $('.field-priorityitem-priority_end_date').find('input').remove();
            $('.field-priorityitem-priority_end_date').find('label').after(data.input2);
            //
            $('#blocked_dates').attr('value',data.date1);

            var forbidden=data.date;

$('#priorityitem-priority_start_date,#priorityitem-priority_end_date').datepicker({
    format: 'dd-mm-yyyy',
    startDate:'d',
    autoclose: true,
    beforeShowDay:function(Date){
        var curr_date = Date.toJSON().substring(0,10);

        if (forbidden.indexOf(curr_date)>-1) return false;
    }
})
}
});
});
});
</script>


<?php if(!$model->isNewRecord)
{ ?>

<script>
    $( "#priorityitem-priority_start_date" ).click(function() {
//  $( document ).ready(function() {
        var path = "<?php echo Url::to(['/priorityitem/loaddatetime']); ?> ";
        var item = $('#priorityitem-item_id').val();
        var priority_id = <?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>;
            $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        dataType:"json",
        url: path, //url to be called
        data: { item: item ,priority_id : priority_id, _csrf : csrfToken}, //data to be send
        success: function( data ) {

            $('.loadingmessage').hide();
            $('.field-priorityitem-priority_start_date').find('input').remove();
            $('.field-priorityitem-priority_start_date').find('label').after(data.input1);

            var forbidden=data.date;

$('input#priorityitem-priority_start_date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    beforeShowDay:function(Date){
        var curr_date = Date.toJSON().substring(0,10);
        if (forbidden.indexOf(curr_date)>-1) return false;
    }
});
    }

});
});

    $( "#priorityitem-priority_end_date" ).click(function() {
        var path = "<?php echo Url::to(['/priorityitem/loaddatetime']); ?> ";
        var item = $('#priorityitem-item_id').val();
        var priority_id = <?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>;
        $('.loadingmessage').show();
        $.ajax({
        type: 'POST',
        dataType:"json",
        url: path, //url to be called
        data: { item: item ,priority_id : priority_id, _csrf : csrfToken}, //data to be send
        success: function( data ) {
            $('.loadingmessage').hide();
            $('.field-priorityitem-priority_end_date').find('input').remove();
            $('.field-priorityitem-priority_end_date').find('label').after(data.input2);

            var forbidden=data.date;

$('input#priorityitem-priority_end_date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    beforeShowDay:function(Date){
        var curr_date = Date.toJSON().substring(0,10);

        if (forbidden.indexOf(curr_date)>-1) return false;
    }
});
}
});
});
</script>
    <?php } ?>

<!-- BEGIN PLUGIN CSS -->
<link href="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.css") ?>" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= Url::to("@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script>
// BEGIN priority item date function
<?php if(!$model->isNewRecord) {?>
$(function(){

    var item = $("#priorityitem-item_id").val();
    var path = "<?php echo Url::to(['/priorityitem/loaddatetime']); ?> ";
    var priority_id = <?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>;
    $.ajax({
        type: 'POST',
        dataType:"json",
        url: path, //url to be called
        data: { item: item, priority_id: priority_id, _csrf : csrfToken}, //data to be send
        success: function( data ) {
                $('.loadingmessage').hide();
                $('#blocked_dates').attr('value',data.date1);
            }

        })
    });
<?php } ?>



 $("#submit1").click(function () {
        if($("#priorityitem-item_id").val()=='')
        {
                $(".field-priorityitem-item_id").addClass('has-error');
                $(".field-priorityitem-item_id").find('.help-block').html('Select item name');
                return false;
        }
        if($("#priorityitem-priority_level").val()=='')
        {
                $(".field-priorityitem-priority_level").addClass('has-error');
                $(".field-priorityitem-priority_level").find('.help-block').html('Select priority level');
                return false;
        }
        if($("#priorityitem-priority_start_date").val()=='')
        {
                $(".field-priorityitem-priority_start_date").addClass('has-error');
                $(".field-priorityitem-priority_start_date").find('.help-block').html('Select start date');
                return false;
        }
        if($("#priorityitem-priority_end_date").val()=='')
        {
                $(".field-priorityitem-priority_end_date").addClass('has-error');
                $(".field-priorityitem-priority_end_date").find('.help-block').html('Select end date');
                return false;
        }

    var va='';
        var path = "<?php echo Url::to(['/priorityitem/checkprioritydate']); ?> ";
        var blocked_dates = $('#blocked_dates').val();
        var item = $('#priorityitem-item_id').val();
        var start = $('#priorityitem-priority_start_date').val();
        var start = start.split("-").reverse().join("-");
        var end = $('#priorityitem-priority_end_date').val();
        var end = end.split("-").reverse().join("-");
        var priority_id = <?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>;
        $.ajax({
        type: 'POST',
        dataType:"json",

        url: path, //url to be called
        data: { item: item, start: start,end: end,blocked_dates : blocked_dates,priority_id : priority_id,_csrf : csrfToken}, //data to be send
        success: function( data ) {
            if(data==1)
            {
                $('.loadingmessage').hide();
                $('#blocked_error').show();
                $("#priorityitem-priority_end_date").removeClass('has-success');
                $("#priorityitem-priority_end_date").addClass('has-error');
                var va = false;
            }
            else if(data==0){
                $('#blocked_error').hide();
                $('.loadingmessage').hide();
                $('form#formId').submit();
            }
        }

});
});

</script>
