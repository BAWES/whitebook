<?php 

use frontend\models\EventItemlink;

$active = [];
$inactive = [];

foreach ($categories as $key => $value) { 
	
	$is_complete = EventItemlink::is_cat_complete($event_id, $value['category_id']); 
    
    if($is_complete) {
    	$active[] = $value;
    }else{
    	$inactive[] = $value;
    }
}

?>
<ul class="progressbar">
  	<?php foreach ($active as $key => $value) { ?>
	  	<li class="active">
	  		<?php if(Yii::$app->language == 'en') { ?>
	        <span><?= $value['category_name'] ?></span>
	        <?php }else{ ?>
	        <span><?= $value['category_name_ar'] ?></span>
	        <?php } ?>
	  	</li>
  	<?php } ?>
  	<?php foreach ($inactive as $key => $value) { ?>
    	<li>
    		<?php if(Yii::$app->language == 'en') { ?>
	        <span><?= $value['category_name'] ?></span>
	        <?php }else{ ?>
	        <span><?= $value['category_name_ar'] ?></span>
	        <?php } ?>
        </li>
    <?php } ?>
</ul>