<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemquestionguide */

$this->title = 'Update Vendor item question guide';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestionguides', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendoritemquestionguide-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
