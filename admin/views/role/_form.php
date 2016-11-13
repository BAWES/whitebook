<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-12 col-sm-12 col-xs-12">

    <?php $form = ActiveForm::begin(); ?>
    
	<?= $form->field($model, 'role_name')->textInput(['maxlength' => 128]) ?>   
	
    <div class="form-group">
        <div class="clearfix">
            <div class="col-md-6"><label>Access list</label></div>
            <div class="col-md-6 text-right">
                <input type="radio" name="check_all" class="check_all" value="1"> Check All |
                <input type="radio" name="check_all" class="check_all" value="0"> UnCheck All
            </div>
        </div>
        <table class="table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th>Controller</th>
                    <th>Methods</th>
                </tr>
            </thead>
            <tbody id="myTable">
                <?php foreach ($action_list as $key => $value) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <td>
                        <?php 

                        foreach ($value as $method) {

                            if(isset($role_access_list[$key]) && in_array($method, $role_access_list[$key])) {
                                $checked = 'checked';
                            } else {
                                $checked = '';
                            }

                         ?>
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" class="check_box" name="access_control[<?= $key ?>][]" value="<?= $method ?>" <?= $checked ?> />
                                <?= $method ?>
                            </label>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                
            </tbody>
        </table>

    </div>

    <br />

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJs("
    $('.check_all').click(function(){

        if ($(this).val() == 1) {
            $(\".check_box\").prop('checked', 'checked');
            console.log(1);
        } else if ($(this).val() == 0) {
            $(\".check_box\").prop('checked', '');
            console.log(0);
        }
    });
",\yii\web\View::POS_READY);
