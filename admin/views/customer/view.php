<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Location;
use common\models\Addresstype;
use common\models\City;

$this->title = 'Customer Details';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<p>
	<?= Html::a('Back', ['index', ], ['class' => 'btn btn-success']) ?>
</p>

<div class="customer-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'customer_name',
            'customer_last_name',
            'customer_email:email',
            'customer_dateofbirth',
            'customer_gender',
            'customer_ip_address',
            'customer_mobile',            
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			]
        ],
    ]) ?>
    

</div>
