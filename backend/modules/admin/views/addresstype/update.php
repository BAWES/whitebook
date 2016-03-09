<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Addresstype */

$this->title = 'Update addresstype: ';
$this->params['breadcrumbs'][] = ['label' => 'Addresstypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="addresstype-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
