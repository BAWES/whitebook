<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Package */

$this->title = 'Update package';
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="package-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
