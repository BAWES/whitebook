<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

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
        ],
    ]) ?>
</div>

<br/>
<br/>
<br/>
<strong><h2>Item Linked</h2></strong>
<div class="customer-view">
	<table class="table table-striped table-bordered detail-view">
		<thead>
			<tr>
				<th>Item Name</th>
				<th>Date Time</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?=ListView::widget([
				'dataProvider' => $providerItems,
				'itemView' => '_items',
				'summary' => '',
			]);

			?>
		</tbody>
	</table>
	<br/>
	<br/>
	<br/>
</div>
<strong><h2>Event Invitees</h2></strong>
<div class="customer-view">
	<table class="table table-striped table-bordered detail-view">
		<thead>
		<tr>
			<th>Invitees ID</th>
			<th>Customer Name</th>
			<th>Email</th>
			<th>Phone Number</th>
		</tr>
		</thead>
		<tbody>
			<?=ListView::widget([
				'dataProvider' => $providerInvitees,
				'itemView' => '_invitees',
				'summary' => '',
			]);
			?>
		</tbody>
	</table>
</div>

