<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Package */

$this->title = 'Update Package: ' . $model->package_id;
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->package_id, 'url' => ['view', 'id' => $model->package_id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="package-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
