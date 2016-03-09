<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */

$this->title = 'Update Customer';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="customer-update">
    <?= $this->render('_form', [
        'model' => $model,'model1' => $model1,'addresstype' => $addresstype,'country' => $country,'location'=>$location,'city'=>$city,
    ]) ?>

</div>
