<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Addresstype */

$this->title = 'Create address type';
$this->params['breadcrumbs'][] = ['label' => 'Addresstypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addresstype-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
