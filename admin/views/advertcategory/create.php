<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Advertcategory */

$this->title = 'Create top category ads';
$this->params['breadcrumbs'][] = ['label' => 'Top categories ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertcategory-create">

    <?= $this->render('_form', [
        'model' => $model,'category'=>$category
    ]) ?>

</div>
