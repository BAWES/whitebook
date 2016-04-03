<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use backend\models\Location;
use backend\models\Addresstype;
use backend\models\City;
/* @var $this yii\web\View */
/* @var $model backend\models\Customer */

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
            'customer_email:email',
            'customer_dateofbirth',
            'customer_gender',
            'customer_mobile',
            	[
            'label'=>'Address Type',			
            'value'  =>  !empty($model1->address_type_id) ? Addresstype::getAddresstype($model1->address_type_id) : '-',
			],
            [
            'label'=>'Country Name',			
            'value'  =>  !empty($model1->country_id) ?City::getCountryName($model1->country_id) : '-',
			],
			[
            'label'=>'City Name',			
            'value'  => !empty($model1->city_id) ?City::getCityname($model1->city_id) : '-',
			],
			[
            'label'=>'Area Name',			
            'value'  => !empty($model1->area_id) ?Location::getlocation($model1->area_id) : '-',
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
			[
            'label'=>'customer address',			
            'value'  => !empty($model->customer_address) ?($model->customer_address) : '-',
			],
        ],
    ]) ?>
    

</div>
