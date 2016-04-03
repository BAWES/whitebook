<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritemquestionansweroption */

$this->title = 'Update Vendor item question answer';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestionansweroptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendoritemquestionansweroption-update">

     <?= $this->render('_form', [
        'model' => $model,'questions' => $questions,
    ]) ?>

</div>
