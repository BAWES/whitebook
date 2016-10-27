<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8 col-sm-8 col-xs-8">	

    <?php $form = ActiveForm::begin(); ?>
    
	<?= $form->field($model, 'role_name')->textInput(['maxlength' => 128]) ?>   
	
    <div class="form-group">
        <label>Access list</label>
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
                                $checked = 'checked';
                            }

                         ?>
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="access_control[<?= $key ?>][]" value="<?= $method ?>" <?= $checked ?> />
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
