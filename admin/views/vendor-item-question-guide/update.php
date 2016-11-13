<?php

use yii\helpers\Html;

$this->title = 'Update Vendor item question guide';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestionguides', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendoritemquestionguide-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
