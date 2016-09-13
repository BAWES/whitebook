<?php 

use yii\helpers\Html;
use yii\helpers\Url;

if (!empty($imageData)) {
	
	foreach ($imageData as $key => $value) {
		$this->render('@web/views/plam/item', [ 
			'value' => $value,
			'customer_events_list' => $customer_events_list
		]);
	}

} else {
	echo "No records found";
}