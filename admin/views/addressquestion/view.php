<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\DetailView;

use common\models\AddressQuestion;
use common\models\AddressType;

$this->title = 'Address Questions';
$this->params['breadcrumbs'][] = ['label' => 'Address Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<p>
	<?= Html::a('Back', ['index', ], ['class' => 'btn btn-success']) ?>
</p>

<div class="address-question-view">
	<table class="table table-striped table-bordered detail-view">
		<tbody>
			<tr>
				<th>Address Type</th>
				<td><?php echo AddressType::getAddressType($model->address_type_id); ?></td>
			</tr>
			<tr>
				<th>Address question</th>
				<td><?php echo AddressQuestion::loadquestion($model->address_type_id);?></td>
			</tr>
		</tbody>
	</table>
</div>
