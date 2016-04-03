<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Vendor */

$this->title = 'Create Address';
$this->params['breadcrumbs'][] = ['label' => 'Vendors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-create">
    <?= $this->render('_form', [
    'model' => $model,'area' => $area,
    ]) ?>
</div>
