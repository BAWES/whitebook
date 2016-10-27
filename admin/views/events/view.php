<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Location;
use common\models\AddressType;
use common\models\City;

$this->title = 'Event Details';
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<p>
	<?= Html::a('Back', ['index', ], ['class' => 'btn btn-success']) ?>
</p>

<div class="customer-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'event_id',
            'customer_id',
            'event_name',
            'event_type',
            'slug',
            'created_by',
            'modified_by',
			[
				'attribute'=>'event_date',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Event date',
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',
			],
			[
				'attribute'=>'modified_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'Modified date',
			]
        ],
    ]) ?>
    

</div>
