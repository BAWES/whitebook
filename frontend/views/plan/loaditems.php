<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
if (!empty($imageData)) {

    foreach ($imageData as $key => $value) {
        require 'item.php';
    }
} else {
    echo "No records found";
}
?>
