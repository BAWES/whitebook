<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use common\models\AddressQuestion;
use common\models\Addresstype;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\AddressQuestion */

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
