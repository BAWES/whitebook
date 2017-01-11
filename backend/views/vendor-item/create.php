<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritem */

$this->title = 'Create item';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritem-create">

    <?= $this->render('_form', [
	        'model' => $model,
	        'itemtype' => $itemtype,
	        'main_categories' => $main_categories,
	        'item_child_categories' => [],
	        'images' => [],
	        'pricing' => [],
    ]) ?>

</div>
