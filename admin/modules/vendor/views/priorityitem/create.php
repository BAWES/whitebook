<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Priorityitem */

$this->title = 'Create priority item';
$this->params['breadcrumbs'][] = ['label' => 'Priorityitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priorityitem-create">
    <?= $this->render('_form', [
     'model' => $model,'priorityitem'=>$priorityitem,'category'=>$category,'subcategory'=>$subcategory,
    ]) ?>

</div>
