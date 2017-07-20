<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VendorReview */

$this->title = 'Create Vendor Review';
$this->params['breadcrumbs'][] = ['label' => 'Vendor Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-review-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
