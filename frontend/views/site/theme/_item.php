<?php 

use yii\helpers\Html;
use yii\helpers\Url;

if (!empty($imageData)) {
	
	foreach ($imageData as $key => $value) {
		echo $this->render('@frontend/views/plan/item', [ 
			'value' => $value,
			'customer_events_list' => $customer_events_list
		]); 
	}

} else {
	echo "No records found";
}