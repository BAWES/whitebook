<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VendorItemCapacityException */

$this->title = 'Create exception dates';
$this->params['breadcrumbs'][] = ['label' => 'Exception dates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vendor-item-capacity-exception-form-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
