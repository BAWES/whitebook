<?php

use yii\helpers\Html;

$this->title = 'Create priority item';
$this->params['breadcrumbs'][] = ['label' => 'Priorityitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="priorityitem-create">

    <?= $this->render('_form', [
     		'model' => $model,
     		'priorityitem' => $priorityitem,
     		'category' => $category,
     		'subcategory' => $subcategory,
     		'childcategory' => $childcategory
    	]); ?>

</div>
