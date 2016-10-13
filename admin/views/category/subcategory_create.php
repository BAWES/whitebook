<?php

use yii\helpers\Html;

$this->title = 'Create Sub category';
$this->params['breadcrumbs'][] = ['label' => 'Sub category', 'url' => ['manage_subcategory']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="category-create">
    <?= $this->render('subcategory_form', [
        'model' => $model,
        'subcategory' => $subcategory
    ]) ?>

</div>
