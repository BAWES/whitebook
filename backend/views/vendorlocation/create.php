<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\vendorlocation */

$this->title = 'Create Vendorlocation';
$this->params['breadcrumbs'][] = ['label' => 'Vendorlocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorlocation-create">

    <?= $this->render('_form', [
        'model' => $model,'cities' => $cities,
    ]) ?>

</div>
