<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Vendorpackages */

$this->title = 'Create Vendorpackages';
$this->params['breadcrumbs'][] = ['label' => 'Vendorpackages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorpackages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
