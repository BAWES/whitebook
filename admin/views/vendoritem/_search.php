<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Category;
use common\models\ItemType;
use common\models\Themes;
use common\models\FeatureGroup;

$category_options = ArrayHelper::map(
        Category::find()->where([
                'category_allow_sale' => 'Yes', 'parent_category_id' => Null
            ])->orderBy('category_name')->asArray()->all(), 
        'category_id', 
        'category_name'
    );

$type_options = ArrayHelper::map(
        ItemType::find()->where(['!=','trash','Deleted'])->asArray()->all(),
        'type_id',
        'type_name'
    );

$theme_options = ArrayHelper::map(
        Themes::find()->where(['!=','trash','Deleted'])->asArray()->all(), 
        'theme_id',
        'theme_name'
    );

$group_options = ArrayHelper::map(
        FeatureGroup::find()->where(['!=','trash','Deleted'])->asArray()->all(), 
        'group_id',
        'group_name'
    );

?>
<div class="vendoritem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">

        <div class="col-md-4">
            <?= $form->field($model, 'item_name') ?>

            <?= $form->field($model, 'vendor_name') ?>

            <?= $form->field($model, 'item_status')->dropDownList(
                [
                    'Active',
                    'Deactive'
                ],           
                ['prompt' => 'All']    
            ); ?> 
        </div>    

        <div class="col-md-4">    
            
            <?= $form->field($model, 'theme_id')->dropDownList(
                $theme_options,           
                ['prompt' => 'All']    
            )->label('Theme'); ?>

            <?= $form->field($model, 'group_id')->dropDownList(
                    $group_options,           
                    ['prompt' => 'All']    
                )->label('Group'); ?>

            
            <?= $form->field($model, 'item_approved')->dropDownList(
                [
                    'Yes',
                    'Pending',
                    'Rejected'
                ],           
                ['prompt' => 'All']    
            );?>     
        </div>

        <div class="col-md-4">        
            <?= $form->field($model, 'type_id')->dropDownList(
                    $type_options,           
                    ['prompt' => 'All']    
                ); ?>

            <?= $form->field($model, 'item_for_sale')->dropDownList(
                [
                    'Yes',
                    'No'
                ],           
                ['prompt' => 'All']    
            ); ?> 
            
        </div>
        
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
