<?php

use yii\helpers\Html;

$this->title = 'Update Vendor item question answer';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestionansweroptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="vendoritemquestionansweroption-update">

     <?= $this->render('_form', [
        'model' => $model,'questions' => $questions,
    ]) ?>

</div>
