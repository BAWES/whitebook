<?php

use yii\helpers\Html;

$this->title = 'Create Vendor item question answer';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestionansweroptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vendoritemquestionansweroption-create">

     <?= $this->render('_form', [
        'model' => $model,'questions' => $questions,
    ]) ?>

</div>
