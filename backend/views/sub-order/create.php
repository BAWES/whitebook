<?php

use yii\helpers\Html;

$this->title = 'Create Sub Order';
$this->params['breadcrumbs'][] = ['label' => 'Sub Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
