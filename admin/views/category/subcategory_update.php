<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = 'Update sub category';
$this->params['breadcrumbs'][] = ['label' => 'Sub category', 'url' => ['manage_subcategory']];
$this->params['breadcrumbs'][] = 'Subcategory_update';
?>
<div class="category-update">
    <?= $this->render('subcategory_form', [
        'model' => $model,'subcategory'=>$subcategory,'userid'=>$userid,'id'=>$id,
    ]) ?>
</div>
