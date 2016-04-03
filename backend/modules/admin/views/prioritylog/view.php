<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Prioritylog */

$this->title = 'Priority Log';
$this->params['breadcrumbs'][] = ['label' => 'Prioritylogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prioritylog-view">

    <p>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'vendor.vendor_name',
            'item.item_name',
            'priority_level',
            [
				'attribute'=>'priority_start_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Start date',			
			],
            [
				'attribute'=>'priority_end_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'End date',			
			],
        ],
    ]) ?>

</div>
