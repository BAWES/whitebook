<?php

use yii\helpers\Html;


$this->title = 'Create child category';
$this->params['breadcrumbs'][] = ['label' => 'Child category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">
    <?= $this->render('child_category_form', [
        'model' => $model,
        'category' => $category,
    ]) ?>
</div>
