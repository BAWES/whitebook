<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Priorityitem */

$this->title = 'Update priority item';
$this->params['breadcrumbs'][] = ['label' => 'Priorityitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="priorityitem-update">

    <?= $this->render('_form', [
     'model' => $model,'priorityitem'=>$priorityitem,'category'=>$category,'subcategory'=>$subcategory,'childcategory'=>$childcategory,
    ]) ?>

</div>
