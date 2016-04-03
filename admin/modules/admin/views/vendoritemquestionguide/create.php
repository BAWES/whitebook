<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemquestionguide */

$this->title = 'Create Vendor item question guide';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestionguides', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritemquestionguide-create">   

    <?= $this->render('_form', [
        'model' => $model,'questions' => $questions,
    ]) ?>

</div>
