<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VendorLocation */

$this->title = 'Add Delivery Area';
$this->params['breadcrumbs'][] = ['label' => 'Manage Area', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorlocation-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
