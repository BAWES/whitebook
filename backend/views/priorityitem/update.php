<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Priorityitem */

$this->title = 'Update priority item';
$this->params['breadcrumbs'][] = ['label' => 'Priorityitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->priority_id, 'url' => ['view', 'id' => $model->priority_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="priorityitem-update">
    <?= $this->render('_form',[
     'model' => $model,'priorityitem'=>$priorityitem,'vendorname'=>$vendorname,'category'=>$category,'subcategory'=>$subcategory,
    ]) ?>

</div>
