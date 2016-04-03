<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Itemtype */

$this->title = 'Update item type: ' . ' ' . $model->type_name;
$this->params['breadcrumbs'][] = ['label' => 'Itemtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="itemtype-update">

   <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
