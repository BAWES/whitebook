<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AddressQuestion */

$this->title = 'Create Address Question';
$this->params['breadcrumbs'][] = ['label' => 'Address Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-question-create">

    <?= $this->render('_form', [
        'model' => $model,'addresstype'=>$addresstype,
    ]) ?>

</div>
