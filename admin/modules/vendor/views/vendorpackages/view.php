<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Vendorpackages */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vendorpackages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorpackages-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'vendor_id',
            'package_id',
            'package_price',
        ],
    ]) ?>

</div>
