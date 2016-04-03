<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
$this->title = 'Update Child category';
$this->params['breadcrumbs'][] = ['label' => 'Child category', 'url' => ['child_category_index']];
$this->params['breadcrumbs'][] = 'Child category update';

?>
<div class="category-update">
    <?= $this->render('child_category_form', ['model' => $model,'parentcategory'=>$parentcategory,'userid'=>$userid,'parentid'=>$parentid,'subcategory_id'=>$subcategory_id,
            'id'=>$id,'subcategory'=>$subcategory,]) ?>
</div>
